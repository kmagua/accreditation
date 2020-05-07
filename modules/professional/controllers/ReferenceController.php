<?php

namespace app\modules\professional\controllers;

use Yii;
use app\modules\professional\models\Reference;
use app\modules\professional\models\ReferenceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ReferenceController implements the CRUD actions for Reference model.
 */
class ReferenceController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create-ajax', 'my-references'],
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
                                $prb = Reference::findOne(Yii::$app->request->get()['id']);
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
        ];
    }

    /**
     * Lists all Reference models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ReferenceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Reference model.
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
     * Creates a new Reference model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Reference();

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
        $model = new Reference();
        $model->user_id = $pid;
        $model->setScenario('new');

        if ($model->load(Yii::$app->request->post()) && $model->saveRecord()) {
            //return $this->redirect(['view', 'id' => $model->id]);
            $model = new Reference();
            $model->user_id = $pid;
            \Yii::$app->session->setFlash('reference_added','Reference Record Added Successfully!');
        }

        return $this->renderAjax('_form', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Reference model.
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
     * 
     * @param type $id
     * @return type
     */
    public function actionUpdateAjax($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->saveRecord()) {
            //return $this->redirect(['view', 'id' => $model->id]);
            \Yii::$app->session->setFlash('reference_added','Reference Record updated Successfully!');
        }

        return $this->renderAjax('_form', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Reference model.
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
     * Finds the Reference model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Reference the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Reference::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    /**
     * 
     * @param type $pid PersonalInformation ID
     */
    public function actionMyReferences($pid)
    {
        $searchModel = new ReferenceSearch();
        $searchModel->user_id = $pid;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $html = $this->renderPartial('my_references', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
        return \yii\helpers\Json::encode($html);
    }
}
