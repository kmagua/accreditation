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
use kartik\mpdf\Pdf;
use app\models\CompanyProfile;

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
                        'actions' => ['update','view', 'upload-receipt', 'download-cert', 'renew-cert', 'revert-rejection'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            if(isset(Yii::$app->request->get()['id'])){                                
                                $application = Application::findOne(Yii::$app->request->get()['id']);
                                if($application){
                                    return Yii::$app->user->identity->isInternal() || CompanyProfile::canAccess($application->company_id);
                                }
                            }                             
                            return false;
                        }
                    ],
                    [
                        'actions' => ['index', 'approve-payment', 'committee-members', 'get-data', 'renewals', 'statuses-report'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return Yii::$app->user->identity->isInternal();
                        }
                    ],
                    [
                        'actions' => ['approval'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return Application::canApprove(Yii::$app->request->get()['level'], Yii::$app->request->get()['id']);
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
        $model->status = "ApplicationWorkflow/draft";
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
        $application_scores = \app\models\ApplicationScore::find()
            ->where(['application_id' => $id, 'committee_id' => $level])->indexBy('id')->orderBy('score_item_id')->all();

        if (Model::loadMultiple($application_scores, Yii::$app->request->post()) && Model::validateMultiple($application_scores)) {
            foreach ($application_scores as $application_score) {                
                $application_score->saveApplicationScore();
            }
            //save committee score
            $committee_score = Yii::$app->request->post()['ApplicationScore']['committee_score'];
            $committee_category = Yii::$app->request->post()['ApplicationScore']['classification'];
            $approval_status = Yii::$app->request->post()['ApplicationScore']['status'];
            $rejection_comment = Yii::$app->request->post()['ApplicationScore']['rejection_comment'];
            \app\models\ApplicationClassification::saveClassification($id, $committee_score, $committee_category, $level, $approval_status, $rejection_comment);
            
            Application::progressOnCommitteeApproval($id, $approval_status, $level);
            
            return $this->redirect('index');
        }

        return $this->render('internal_approval', [
            'application_scores' => $application_scores,
            'level' =>$level,'app_id'=>$id
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
            if($model->savePayment()){
                //$status = ($l == 1) ? "application-paid" : "certificate-paid";
                if($model->application->progressWorkFlowStatus("certificate-paid")){
                    return "Payment submitted successfully. You will receive an automated notification email once the payment has been confirmed.";
                }
                return "Error updating payment details.";
            }
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
        $model->loadApplicationCommitteeMembers($l);
        
        if ($model->load(Yii::$app->request->post()) && $model->saveApplicationCommitteeMembers($l)) {
            $next_step = ($l == 1) ? 'at-secretariat': 'at-committee';
            if($model->application->progressWorkFlowStatus($next_step)){
                \Yii::$app->session->setFlash('members_added','Members assigned successfully!');
            }
            //$this->redirect(['index']);
        }
        
        return $this->renderAjax('app_committee_members', [
            'model' => $model, 'level' => $l
        ]);
    }
    
    /**
     * 
     * @param type $id
     * @return type
     */
    public function actionDownloadCert($id)
    {
        $application = $this->findModel($id);
        if($application->status != "ApplicationWorkflow/completed"){
            throw new \yii\web\HttpException(403, "You cannot download a certificate until it is approved.");
        }
        $app_classification = \app\models\ApplicationClassification::find()->where(['application_id'=>$id])->orderBy("id desc")->one();
        //$sn = bin2hex($id * 53);
        $content = $this->renderPartial('certificate', ['application' => $application, 'app_class' => $app_classification]);
        $filename = "cert- " .$application->id . ".pdf";

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
                'SetHeader'=>['SN: '. $application->certificate_serial], 
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render(); 
    }
    
    public function actionRenewCert($id, $cid, $t)
    {
        $model = new Application();
        $model->initRenewal($id, $cid, $t);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->setFlash('application_submitted','Application Renewal Submitted Successfully!');
            $this->redirect(['company-profile/view','id'=>$cid,'#'=>'application_data_tab']);
        }
        
        return $this->render('_renewal_form', [
            'model' => $model,
        ]);
    }
    
    public function actionGetData($id, $sec)
    {
        $application = $this->findModel($id);
        switch($sec){
            case 'staff_directors';
                return $this->renderAjax('view_staff_data', ['app_id'=>$application->id, 's'=>1]);
            case 'staff_staff';
                return $this->renderAjax('view_staff_data', ['app_id'=>$application->id, 's'=>2]);
            case 'financial_status';
                return $this->renderAjax('view_fin_status', ['model'=>$application]);
            case 'company_exp';
                return $this->renderAjax('view_company_exp', ['app_id'=>$application->id]);
            case 'biz_permit';
                return $this->renderAjax('view_biz_permit', ['app_id'=>$application->id,
                    'cid' =>$application->company_id]);
        }
    }
    
    /**
     * 
     * @param type $id
     * @param type $l
     * @return type
     */
    public function actionRevertRejection($id, $l)
    {
        $model = $this->findModel($id);
        $model->setScenario('revert_rejection');
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $next_step = ($l == 1) ? 'at-secretariat': 'at-committee';
            if($model->progressWorkFlowStatus($next_step)){
                //\Yii::$app->session->setFlash('rejection_reversed','Application status updated!');
                return "Application status updated!";
            }            
        }
        
        return $this->renderAjax('revert_rejection', [
            'model' => $model
        ]);
    }
    
    /**
     * 
     * @return type
     */
    public function actionRenewals()
    {
        $searchModel = new ApplicationSearch();
        $dataProvider = $searchModel->searchRenewals(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'is_renewal' => 'yes'
        ]);
    }
    
    /**
     * 
     * @return type
     */
    public function actionStatusesReport()
    {        
        $records = \Yii::$app->db->createCommand("
            SELECT COUNT(id) id, CONCAT_WS(' ', `status`, '(', COUNT(id), ')') `status`
            FROM `accreditcomp`.application WHERE parent_id IS NULL GROUP BY `status`")
            ->queryAll();
        $data = array_column($records, 'id');
        $values = $data;
        $total_applications = array_sum($data);
        //echo  $total_applications; exit;
        array_walk($data, \app\models\Utility::class . '::get_percentages_from_array', $total_applications);
        
        //();
        //echo $total_applications; exit;
        //$percentages = 
        $statuses = array_column($records, 'status');
        return $this->render('statuses_report', [
            'data_percentage' => $data,
            'labels' => $statuses,
            'data_values' => $values,
        ]);
    }
}
