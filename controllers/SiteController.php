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
                'fixedVerifyCode' => null,
                'testLimit' => 1
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
        //echo \Yii::getAlias('@webroot/images/francis.png'); exit;
        if(!Yii::$app->user->isGuest && Yii::$app->user->identity->isInternal()){
            return $this->render('index_internal');
        }
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
        if ($model->load(Yii::$app->request->post())) {
            if($model->login()){
                $uid = $model->getUser()->getId();
                \app\models\LoginTrials::setStatusToZero($uid);
                \app\models\User::updateLastLoginTime($uid);
                return $this->redirectToProfile();
            }else{
                //if the account is still active
                if($model->getUser() && $model->getUser()->status){
                    \app\models\LoginTrials::checkAccountOnUnsuccessfulLogin($model->username);
                }
            }
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
    
    /**
     * 
     * @return type
     */
    public function actionValidate()
    {
        if (Yii::$app->request->post()) {
            $cert_no = Yii::$app->request->post()['certificate_no'];
            $company = \app\models\Application::find()->where(['certificate_serial' =>$cert_no])->orderBy('id desc')->one();
            if($company){
                $app_classification = \app\models\ApplicationClassification::find()->where(['application_id'=>$company->id])->orderBy("id desc")->one();
                $details = $company->company->company_name . ' - Registration No: ' . $company->company->business_reg_no . ' is accredited under <strong>' . 
                    $company->accreditationType->name . ' (' . $app_classification->classification .')</strong> category, Valid Till: '. date('jS M Y', strtotime($company->initial_approval_date . "+ 1 year"));
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
        if(Yii::$app->user->identity->isInternal() || \Yii::$app->user->identity->inGroup('pdtp')){
            return $this->redirect(['site/index']);
        }
        $company_profile = \app\models\CompanyProfile::findOne(['user_id' => \Yii::$app->user->identity->user_id]);
        if($company_profile){
            return $this->redirect(['company-profile/view', 'id' => $company_profile->id]);
        }else{
            return $this->redirect(['company-profile/create']);
        }
    }
    
    public function actionTestPut()
    {
        $curl = curl_init();
        $id = 10;
        $status = 'Confirmed';
        $token = md5(date('Y-d-m')) . strtoupper(dechex($id));

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://localhost/accreditation/web/application-service/update-payment-status",
            CURLOPT_RETURNTRANSFER => true,          
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => json_encode( array( 'applic_Id'=> $id, "status" => $status, "token"=> $token) ), // Data sent in json format.
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/json",
                "x-api-key: 4a5fcaa28975b99da6b8221f8fdf7b72A"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          echo $response;
        }
    }
    
    public function actionTestDraft($id)
    {
        $model = \app\models\Application::findOne($id);
        $model->checkCompanyExists(); 
        //$model->updateApplicationPaymentOnERP();
    }
    
    public function actionSiteMap()
    {
        return $this->render('site_map');
    }
}
