<?php

namespace app\controllers;

use Yii;
use app\models\Application;
use app\models\ApplicationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

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
                        'actions' => ['index','approval'],
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
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('view_full', [
            'model' => $model,
            'level' => $level,
        ]);
    }
    
    /**
     * 
     * @param type $id
     * @param type $l
     */
    public function actionUploadReceipt($id, $l)
    {
        $model = new \app\models\Payment();
        $model->application_id = $id;
        $model->level = $l;
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->setFlash('application_submitted','Application Submitted Successfully!');
            $this->redirect(['company-profile/view','id'=>$cid,'#'=>'application_data_tab']);
        }
        
        return $this->renderAjax('../payment/_form', [
            'model' => $model,
        ]);
    }
}
