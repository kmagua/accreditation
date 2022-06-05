<?php

namespace app\controllers;

use Yii;
use app\models\CompanyStaff;
use app\models\CompanyStaffSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * CompanyStaffController implements the CRUD actions for CompanyStaff model.
 */
class CompanyStaffController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create','view','update','delete','staff-data','new'],
                'rules' => [
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['staff-data','new'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            if(isset(Yii::$app->request->get()['cid'])){
                                return Yii::$app->user->identity->isInternal() || \app\models\CompanyProfile::canAccess(Yii::$app->request->get()['cid']);
                            }                             
                            return false;
                        }
                    ],
                    [
                        'actions' => ['view','update', 'update-ajax', 'delete-ajax'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            if(isset(Yii::$app->request->get()['id'])){
                                $staff= CompanyStaff::findOne(Yii::$app->request->get()['id']);
                                if($staff)
                                   {
                                   return Yii::$app->user->identity->isInternal() || \app\models\CompanyProfile::canAccess($staff->company_id); 
                                   }                                
                            }                             
                            return false;
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
     * Lists all CompanyStaff models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CompanyStaffSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CompanyStaff model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CompanyStaff model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CompanyStaff();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CompanyStaff model.
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

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }
    
    /**
     * Updates an existing CompanyStaff model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdateAjax($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return "updated Successfully";
        }

        return $this->renderAjax('_form', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CompanyStaff model.
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
     * Deletes an existing CompanyStaff model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteAjax($id)
    {
        $application = \app\models\ApplicationStaff::find()->where(['staff_id' => $id])->one();        
        if(!$application){
            $this->findModel($id)->delete();
            return 'Deleted successfully';
        }else{
            return '<p style="color:red">The member is linked to an existing application hence cannot be removed.</p>';
        }
    }

    /**
     * Finds the CompanyStaff model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CompanyStaff the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CompanyStaff::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    /**
     * 
     * @param type $cid Company ID
     */
    public function actionStaffData($cid)
    {
        $searchModel = new CompanyStaffSearch();
        $searchModel->company_id = $cid;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $html = $this->renderPartial('company_staff_list', [
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
        $model = new CompanyStaff();
        $model->company_id = $cid;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model = new CompanyStaff();
            $model->company_id = $cid;
            \Yii::$app->session->setFlash('staff_added','Staff details added successfully!');
        }

        return $this->renderAjax('new', [
            'model' => $model,
        ]);
    }
}
