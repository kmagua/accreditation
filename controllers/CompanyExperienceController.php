<?php

namespace app\controllers;

use Yii;
use app\models\CompanyExperience;
use app\models\CompanyExperienceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CompanyExperienceController implements the CRUD actions for CompanyExperience model.
 */
class CompanyExperienceController extends Controller
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
        ];
    }

    /**
     * Lists all CompanyExperience models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CompanyExperienceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CompanyExperience model.
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
     * Creates a new CompanyExperience model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CompanyExperience();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    
    /**
     * @param type $cid Company Profile ID
     * @return string
     */
    public function actionCreateAjax($cid)
    {
        $model = new CompanyExperience();
        $model->company_id = $cid;
        $model->setScenario('create');

        if ($model->load(Yii::$app->request->post()) && $model->saveCompanyExperience()){            
            \Yii::$app->session->setFlash('ce_added','Company project details added successfully!');
            $model = null;         
        }
        
        $searchModel = new CompanyExperienceSearch();
        $searchModel->company_id = $cid;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->renderAjax('index_with_form', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    /**
     * Updates an existing CompanyExperience model.
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

        if ($model->load(Yii::$app->request->post()) && $model->saveCompanyExperience()) {
            
            $searchModel = new CompanyExperienceSearch();
            $searchModel->company_id = $model->company_id;
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
     * Deletes an existing CompanyExperience model.
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
     * Finds the CompanyExperience model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CompanyExperience the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CompanyExperience::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    /**
     * 
     * @param type $cid Company Profile ID
     */
    public function actionData($cid)
    {
        $searchModel = new CompanyExperienceSearch();
        $searchModel->company_id = $cid;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->renderAjax('index_with_form', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
