<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
                        'actions' => ['register', 'confirm-user-account','reset-password', 'set-new-password'],
                        'allow' => true,
                        'roles' => ['?'],                        
                    ],
                    [
                        'actions' => ['update','view'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            if(isset(Yii::$app->request->get()['id'])){                                
                                return Yii::$app->user->identity->isInternal() || \Yii::$app->user->identity->user_id == Yii::$app->request->get()['id'];
                            }                             
                            return false;
                        }
                    ],
                    [
                        'actions' => ['my-profile'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index','approval','approve-payment', 'committee-members', 'change-role', 'new'],
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionRegister()
    {
        $model = new User();
        $model->setScenario('register');
        $model->status = 0;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->setFlash('user_registration','Your account has been registered and an "activation" email sent to "your email".');
            return $this->redirect(['site/index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['user/index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    /**
     * 
     * @param type $id
     * @param type $h
     * @return type
     */
    public function actionConfirmUserAccount($id, $h)
    {
        $pass_reset = \app\models\PasswordReset::findOne(['user_id' => $id, 'hash' => $h, 'status'=>0]);
        if($pass_reset){
            $user = $this->findModel($id);
            $user->status = 1;
            $user->save(false);
            $pass_reset->status = 1;
            $pass_reset->hash = '00';
            $pass_reset->save(false);
            \Yii::$app->session->setFlash('user_confirmation','Your account has been activated. Login here.');
            return $this->redirect(['site/login']);
        }else{
            echo "Invalid Account Confirmation Details"; exit;
        }
    }
    
    /**
     * 
     * @return type
     */
    public function actionResetPassword()
    {
        if (Yii::$app->request->post()) {
            \app\models\PasswordReset::passwordReset(Yii::$app->request->post()['kra_pin_number']);
            \Yii::$app->session->setFlash('account_reset','Your account password reset link has been sent to your email.');
            return $this->redirect(['site/index']);
        }

        return $this->render('reset_password');
    }
    
    /**
     * 
     * @param type $id
     * @param type $ph
     * @return type
     * @throws \yii\web\HttpException
     */
    public function actionSetNewPassword($id, $ph)
    {
        $pass_reset = \app\models\PasswordReset::find()->
            where(['user_id' => $id, 'hash' => $ph, 'status'=>0])->one();
        if(!$pass_reset){
            throw new \yii\web\HttpException(403, "Access denied");
        }
        $model = User::findOne($id);
        $model->setScenario('password_update');
        $model->status = 1;
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $pass_reset->status = 1;
            $pass_reset->hash = '00';
            $pass_reset->save(false);
            \Yii::$app->session->setFlash('user_confirmation', 'Password updated. Use your new password to login.');
            return $this->redirect(['site/login']);
        }

        return $this->render('password_update', [
            'model' => $model,
        ]);
    }
    
    /**
     * 
     * @param type $id
     * @return type
     */
    public function actionChangeRole($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //\Yii::$app->session->setFlash('user_confirmation','Password updated. Use your new password to login.');
            return $this->redirect(['user/index']);
        }

        return $this->render('change_role', [
            'model' => $model,
        ]);
    }
    
    /**
     * 
     * @return type
     */
    public function actionMyProfile()
    {
        //$model = $this->findModel(\Yii::$app->user->identity->user_id);
        return $this->render('user_view', [
            'model' => $this->findModel(\Yii::$app->user->identity->user_id),
        ]);
    }
    
    /**
     * 
     * @return type
     */
    public function actionNew()
    {
        $model = new User();
        $model->setScenario('register_internal');
        //$model->generateKRAPIN();
        $model->status = 1;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->setFlash('user_registration','User account successfully registered and is ready for use.');
            return $this->redirect(['site/index']);
        }

        return $this->render('new_user_internal', [
            'model' => $model,
        ]);
    }
}
