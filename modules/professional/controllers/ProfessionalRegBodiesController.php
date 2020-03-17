<?php

namespace app\modules\professional\controllers;

use Yii;
use app\modules\professional\models\ProfessionalRegBodies;
use app\modules\professional\models\ProfessionalRegBodiesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ProfessionalRegBodiesController implements the CRUD actions for ProfessionalRegBodies model.
 */
class ProfessionalRegBodiesController extends Controller
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
                        'actions' => ['create-ajax', 'my-memberships'],
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
                        'actions' => ['update-ajax', 'view'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            if(isset(Yii::$app->request->get()['id'])){                                
                                $prb = ProfessionalRegBodies::findOne(Yii::$app->request->get()['id']);
                                if($prb){
                                    return Yii::$app->user->identity->isInternal() 
                                        || \app\modules\professional\models\PersonalInformation::canAccess($prb->user_id);
                                }
                            }
                            return false;
                        }
                    ],
                    [
                        'actions' => ['index'],
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
     * Lists all ProfessionalRegBodies models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProfessionalRegBodiesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProfessionalRegBodies model.
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
     * Creates a new ProfessionalRegBodies model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProfessionalRegBodies();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    /**
     * 
     * @param type $pid PersonalInformation ID
     * @return type
     */
    public function actionCreateAjax($pid)
    {
        $model = new ProfessionalRegBodies();
        $model->user_id = $pid;

        if ($model->load(Yii::$app->request->post()) && $model->saveRecord()) {
            //return $this->redirect(['view', 'id' => $model->id]);
            $model = new ProfessionalRegBodies();
            $model->user_id = $pid;
            \Yii::$app->session->setFlash('membership_added','Professional Membership Record Added Successfully!');
        }

        return $this->renderAjax('_form', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ProfessionalRegBodies model.
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
    
    public function actionUpdateAjax($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->saveRecord()) {
            //return $this->redirect(['view', 'id' => $model->id]);
            \Yii::$app->session->setFlash('membership_added','Professional Membership Record Saved Successfully!');
        }

        return $this->renderAjax('_form', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ProfessionalRegBodies model.
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
     * Finds the ProfessionalRegBodies model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProfessionalRegBodies the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProfessionalRegBodies::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    /**
     * 
     * @param type $pid PersonalInformation ID
     */
    public function actionMyMemberships($pid)
    {
        $searchModel = new ProfessionalRegBodiesSearch();
        $searchModel->user_id = $pid;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $html = $this->renderPartial('my_memberships', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
        return \yii\helpers\Json::encode($html);
    }
}
