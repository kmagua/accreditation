<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'reports'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' =>['reports'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return Yii::$app->user->identity->isInternal();
                        }
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }   

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirectToProfile();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    
    public function actionCompanyAccreditationPrerequisites()
    {
        return $this->render('company_cat_prereq');
    }
    
    public function actionValidate()
    {
        if (Yii::$app->request->post()) {
            $cert_no = Yii::$app->request->post()['certificate_no'];
            $company = \app\models\Application::find()->where(['certificate_serial' =>$cert_no])->one();
            if($company){
                $details = $company->company->company_name . ' - Registration No: ' . $company->company->business_reg_no;
                Yii::$app->session->setFlash('cert_search', $details);
            }else{
                $staff = \app\modules\professional\models\Application::find()->where(['cert_serial' =>$cert_no])->one();
                if($staff){
                    $details = $staff->user->other_names . ' '. $staff->user->first_name 
                        . ' ' . $staff->user->last_name . " - ID No." . $staff->user->idno;
                    Yii::$app->session->setFlash('cert_search', $details);
                }else{
                    Yii::$app->session->setFlash('cert_search', 'Not found!!');
                }
            }
        }
        return $this->render('search');
    }
    
    /**
     * 
     * @return type
     */
    public function actionCodeOfConduct()
    {
        return $this->render('code_of_conduct');
    }
    
    public function actionReports()
    {
        return $this->render('reports');
    }
    
    public function actionFaqs()
    {
        return $this->render('faqs');
    }
    
    public function redirectToProfile()
    {
        if(Yii::$app->user->identity->isInternal()){
            return $this->redirect(['site/index']);
        }
        $company_profile = \app\models\CompanyProfile::findOne(['user_id' => \Yii::$app->user->identity->user_id]);
        if($company_profile){
            return $this->redirect(['company-profile/view', 'id' => $company_profile->id]);
        }else{
            return $this->redirect(['company-profile/create']);
        }
    }
}
