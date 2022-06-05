<?php

namespace app\controllers;

use Yii;
use app\models\ProfessionalCertification;
use app\models\ProfessionalCertificationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ProfessionalCertificationController implements the CRUD actions for ProfessionalCertification model.
 */
class ProfessionalCertificationController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['create','view','update','delete', 'index'],
                'rules' => [
                    [
                        'actions' => ['create-ajax', 'data'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            if(isset(Yii::$app->request->get()['sid'])){
                                $st = \app\models\CompanyStaff::findOne(Yii::$app->request->get()['sid']);
                                if($st){
                                    return (Yii::$app->user->identity->id == $st->company->user_id) || Yii::$app->user->identity->isAdmin(false);
                                }
                            }                             
                            return false;
                        }
                    ],
                    [
                        'actions' => ['update-ajax','view', 'delete-ajax'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            if(isset(Yii::$app->request->get()['id'])){
                                $pc = \app\models\ProfessionalCertification::findOne(Yii::$app->request->get()['id']);
                                if($pc){
                                    return (Yii::$app->user->identity->id == $pc->staff->company->user_id) || Yii::$app->user->identity->isAdmin(false);
                                }
                            }                             
                            return false;
                        }
                    ],
                    [
                        'actions' => ['index', 'view'],
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
     * Lists all ProfessionalCertification models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProfessionalCertificationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProfessionalCertification model.
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
     * Creates a new ProfessionalCertification model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProfessionalCertification();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    
    /**
     * @param type $sid
     * @return string
     */
    public function actionCreateAjax($sid)
    {
        $model = new ProfessionalCertification();
        $model->staff_id = $sid;

        if ($model->load(Yii::$app->request->post()) && $model->saveProfessionalCertification()){
            \Yii::$app->session->setFlash('pc_added','Successfully saved, add another certification or close the dialog.');
            $model = null;
        }
        $searchModel = new ProfessionalCertificationSearch();
        $searchModel->staff_id = $sid;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->renderAjax('index_with_form', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    /**
     * Updates an existing ProfessionalCertification model.
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
     * Updates an existing AcademicQualification model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdateAjax($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->saveProfessionalCertification();            
            
            $searchModel = new ProfessionalCertificationSearch();
            $searchModel->staff_id = $model->staff_id;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->renderAjax('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }

        return $this->renderAjax('_form', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ProfessionalCertification model.
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
     * Deletes an existing AcademicQualification model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteAjax($id)
    {
        return $this->findModel($id)->delete();
    }

    /**
     * Finds the ProfessionalCertification model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProfessionalCertification the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProfessionalCertification::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    /**
     * 
     * @param type $sid Staff ID
     * @param type $e Whether to show form
     * @return type
     */
    public function actionData($sid, $e=1)
    {
        $searchModel = new ProfessionalCertificationSearch();
        $searchModel->staff_id = $sid;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if($e){
            return $this->renderAjax('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,            
            ]);
        }else{
            return $this->renderAjax('gridview', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,            
            ]);
        }
    }
}
