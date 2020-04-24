<?php

namespace app\modules\professional\controllers;

use Yii;
use app\modules\professional\models\Application;
use app\modules\professional\models\ApplicationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use kartik\mpdf\Pdf;

/**
 * ApplicationController implements the CRUD actions for Application model.
 */
class ApplicationController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create-ajax', 'my-application'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            if(isset(Yii::$app->request->get()['pid'])){
                                return Yii::$app->user->identity->isInternal() 
                                    || \app\modules\professional\models\PersonalInformation::canAccess(Yii::$app->request->get()['pid']);
                            }                             
                            return false;
                        }
                    ],
                    [
                        'actions' => ['upload-receipt'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            if(isset(Yii::$app->request->get()['id'])){                                
                                $application = Application::findOne(Yii::$app->request->get()['id']);
                                if($application){
                                    return \app\modules\professional\models\PersonalInformation::canAccess($application->user_id)
                                        || Yii::$app->user->identity->inGroup('admin');
                                }
                            }                             
                            return false;
                        }
                    ],
                    [
                        'actions' => ['update-ajax', 'view', 'download-cert'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            if(isset(Yii::$app->request->get()['id'])){                                
                                $application = Application::findOne(Yii::$app->request->get()['id']);
                                if($application){
                                    return Yii::$app->user->identity->isInternal() 
                                        || \app\modules\professional\models\PersonalInformation::canAccess($application->user_id);
                                }
                            }                             
                            return false;
                        }
                    ],
                    [
                        'actions' => ['index', 'approve-payment'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return Yii::$app->user->identity->isInternal();
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Application models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ApplicationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Application model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Application model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Application();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    
    /**
     * 
     * @param type $pid
     * @return type
     */
    public function actionCreateAjax($pid)
    {
        $model = new Application();
        $model->user_id = $pid;
        $model->setScenario('create');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->renderAjax('my_application', [
                'model' => $model,
            ]);
        }

        return $this->renderAjax('_form', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Application model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Application model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Application model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Application the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Application::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    /**
     * 
     * @param type $pid PersonalInformation ID
     */
    public function actionMyApplication($pid)
    {
        if (($model = Application::findOne(['user_id' => $pid])) === null) {
            $model = new Application();
            $model->user_id = $pid;
        }
        if($model->isNewRecord){
            $html = $this->renderPartial('_form', [
                'model' => $model
            ]);
        }else{
            $html = $this->renderPartial('my_application', [
                'model' => $model
            ]);
        }
        return \yii\helpers\Json::encode($html);
    }
    
    /**
     * 
     * @param type $id
     * @return type
     */
    public function actionDownloadCert($id)
    {
        $application = $this->findModel($id);
        
        $sn = bin2hex($id * 53);
        $content = $this->renderPartial('certificate', ['application' => $application]);
        $filename = "professional-cert- " .$application->id . ".pdf";

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_LANDSCAPE, 
            // stream to browser inline
            'destination' => Pdf::DEST_DOWNLOAD,
            'filename' => $filename,
            // your html content input
            'content' => $content,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}', 
             // set mPDF properties on the fly
            'options' => ['title' => 'Krajee Report Title'],
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>['SN: '. $sn], 
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render(); 
    }
    /**
     * 
     * @param type $id
     * @return string
     */
    public function actionUploadReceipt($id)
    {
        $model = \app\modules\professional\models\Payment::find()->where(['application_id' => $id])->one();
        if(!$model){
            $model = new \app\modules\professional\models\Payment;
            $model->application_id = $id;            
            $model->billable_amount = $model->application->category->accreditation_fee;
        }
        
        if ($model->load(Yii::$app->request->post())) {
            if($model->savePayment()){
                return "Payment submitted successfully. You will receive an automated notification email once the payment has been confirmed.";
            }
            return "Error updating payment details.";
        }
        
        return $this->renderAjax('../payment/_form_receipt', [
            'model' => $model,
        ]);
    }
    
    /**
     * 
     * @param type $id Application ID
     * @param type $l Internal Approval Level : 1 = Secretariat, 2 = Committee
     */
    public function actionApprovePayment($id)
    {
        $model = \app\modules\professional\models\Payment::find()->where(['application_id' => $id])->one();
        if(!$model){
            throw new \Exception("Record not found!");
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->application->initial_approval_date = date('Y-m-d');
            $model->application->status =( $model->status =='confirmed')?4:5;
            if($model->application->cert_serial == ''){
                $model->application->cert_serial = strtoupper(dechex($model->application->id * 100000081));
            }
            $model->application->save(false);
            return "Payment status updated successfully.";
        }
        
        return $this->renderAjax('../payment/approve_payment_receipt', [
            'model' => $model,
        ]);
    }
}
