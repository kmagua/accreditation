<?php

namespace app\controllers;

use Yii;
use app\models\Application;
use app\models\ApplicationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\base\Model;

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
                        'actions' => ['new','applications','create'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            if(isset(Yii::$app->request->get()['cid'])){
                                return Yii::$app->user->identity->isInternal() || CompanyProfile::canAccess(Yii::$app->request->get()['cid']);
                            }                             
                            return false;
                        }
                    ],
                    [
                        'actions' => ['update','view', 'upload-receipt'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            if(isset(Yii::$app->request->get()['id'])){
                                $cid = Application::findOne(Yii::$app->request->get()['id'])->company_id;
                                return Yii::$app->user->identity->isInternal() || CompanyProfile::canAccess($cid);
                            }                             
                            return false;
                        }
                    ],
                    [
                        'actions' => ['index','approval','approve-payment', 'committee-members'],
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
    public function actionCreate($cid)
    {
        $model = new Application();
        $model->company_id = $cid;
        $model->setScenario('create_update');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->redirect(['company-profile/view','id'=>$cid,'#'=>'application_data_tab']);
        }

        return $this->render('create', [
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
        $model->setScenario('create_update');
        $model->loadExperienceData();
        $model->loadStaffData();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->redirect(['company-profile/view','id'=>$model->company_id,'#'=>'application_data_tab']);
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
     * @param type $cid Company ID
     */
    public function actionApplications($cid)
    {
        $searchModel = new ApplicationSearch();
        $searchModel->company_id = $cid;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $html = $this->renderPartial('company_applications', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
        return \yii\helpers\Json::encode($html);
    }
    
    /**
     * 
     * @param type $cid Company ID
     */
    public function actionNew($cid)
    {
        $model = new Application();
        $model->company_id = $cid;
        $model->setScenario('create');
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->setFlash('application_submitted','Application Submitted Successfully!');
            $this->redirect(['company-profile/view','id'=>$cid,'#'=>'application_data_tab']);
        }
        
        return $this->renderAjax('_form', [
            'model' => $model,
        ]);
    }
    
    /**
     * Approval for Application
     * @param type $id Application ID
     * @param type $level 1= Secretariat, 2 = Committee
     */
    public function actionApproval($id, $level)
    {
        $sql = "SELECT scoreItem.id sc_id,category, specific_item,score_item,aps.id, score,maximum_score FROM `application_score` aps RIGHT JOIN `score_item` scoreItem ON scoreItem.id=aps.`score_item_id`
            WHERE IFNULL(aps.`application_id`,:application_id) = :application_id AND IFNULL(`committee_id`, :committee_id) = :committee_id order by sc_id";
        $data = Yii::$app->db->createCommand($sql, [':committee_id' => $level,':application_id'=>$id])->query();
        

        /*if (Model::loadMultiple($application_scores, Yii::$app->request->post()) && Model::validateMultiple($application_scores)) {
            foreach ($application_scores as $application_score) {
                $application_score->save(false);
            }
            return $this->redirect('index');
        }*/

        return $this->render('internal_approval', [
            'application_scores' => $data,
            'level' =>$level,
        ]);
    }
    
    /**
     * 
     * @param type $id Application ID
     * @param type $l Internal Approval Level : 1 = Secretariat, 2 = Committee
     */
    public function actionUploadReceipt($id, $l)
    {
        $model = \app\models\Payment::find()->where(['application_id' => $id, 'level' => $l])->one();
        if(!$model){
            $model = new \app\models\Payment();
            $model->application_id = $id;
            $model->level = $l;
            $model->billable_amount = $model->application->getPayableAtLevel($l);
        }
        
        if ($model->load(Yii::$app->request->post())) {
            $model->savePayment();
            $model->application->progressWorkFlowStatus('application-paid');
            return "Payment submitted successfully. You will receive an automated notification email once the payment has been confirmed.";
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
    public function actionApprovePayment($id, $l)
    {
        $model = \app\models\Payment::find()->where(['application_id' => $id, 'level' => $l])->one();
        if(!$model){
            throw new \Exception("Record not found!");
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->updateApplicationPaymentStatus();           
            return "Payment status updated successfully.";
        }
        
        return $this->renderAjax('../payment/approve_payment_receipt', [
            'model' => $model,
        ]);
    }
    
    /**
     * 
     * @param type $id
     * @param type $l
     * @return string
     * @throws \Exception
     */
    public function actionCommitteeMembers($id, $l)
    {
        $model = new \app\models\ApplicationCommitteMember();
        $model->application_id = $id;
        $model->loadApplicationCommitteeMembers();
                
        if ($model->load(Yii::$app->request->post()) && $model->saveApplicationCommitteeMembers()) {
            $model->application->progressWorkFlowStatus('at-secretariat');           
            $this->redirect(['index']);
        }
        
        return $this->render('app_committee_members', [
            'model' => $model, 'level' => $l
        ]);
    }
}
