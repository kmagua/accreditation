<?php
use yii\grid\GridView;
use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = 'ICT Authority Supplier Accreditation System';
?>

<div class="row">
    <h1 style="text-align: center"><?= $this->title ?></h1>
</div>
<div class="site-index">
    <div class="body-content">
        <div class="row">
            <?php if(Yii::$app->session->hasFlash('user_registration')): ?>
            <div class="alert alert-success alert-dismissable col-lg-12 col-md-12">
                <h4><?php echo Yii::$app->session->getFlash('user_registration'); ?></h4>
            </div>
            <?php endif; ?>
            
            <?php if(Yii::$app->session->hasFlash('account_reset')): ?>
            <div class="alert alert-success alert-dismissable col-lg-12 col-md-12">
                <h4><?php echo Yii::$app->session->getFlash('account_reset'); ?></h4>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="row" style="border-bottom: 1px solid red">            
            <div class="col-lg-11 col-md-6" style="border-top:1px solid red;">
                <h2 style="color: #c11d35">Requirements</h2>

                <ol>
                   <li>  Company profile</li>
                   <li>  Certificate of incorporation</li>
                   <li>  Business permit</li>
                   <li>  KRA compliance certificate</li>
                   <li>  CVs , IT related university certificate, project management certificate national id copies and KRA pin for all of all directors</li>
                   <li>  CVs, IT related degree, professional certifications, certification in project management for all technical staff</li>
                   <li>  LPOs, LSOs, and Recommendation Letters</li>
                   <li>  Bank statements and audited accounts for the past three (3) years;</li>                   
                   <li>  Certificate of partnership, where applicable</li>                   
                </ol>
                <p style='color:red'>There are 8 categories for Supplier/Company Accreditation. <?= Html::a('Click Here', ['site/company-accreditation-prerequisites']) ?> to see the prerequisites for each.</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-4 col-md-4">            
                <?php if(Yii::$app->user->isGuest): ?>
                <h2>Where to start</h2>
                <?= Html::a('Login',['/site/login']) ?> or <?= Html::a('Register',['/user/register']) ?> an account to apply for <span style="color:red"><i>
                        Supplier/Company Certificate</i></span> <!--or <span style='color:red'><i>
                                ICT Professional Certification!</i></span>--><br>
                <?php elseif(!Yii::$app->user->identity->isInternal()): ?>
                <!--<h2>Choose application Type</h2>-->
                <?php
                $company_profile = \app\models\CompanyProfile::findOne(['user_id' => \Yii::$app->user->identity->user_id]);
                if($company_profile){
                    echo Html::a('My Profile', ['company-profile/view', 'id' => $company_profile->id], ['class' => 'btn btn-danger', 'style' =>"margin-top:3px"]);
                    //return $this->redirect(['company-profile/view', 'id' => ]);
                }else{
                    echo Html::a('Add Company Profile', ['company-profile/create'], ['class' => 'btn btn-danger', 'style' =>"margin-top:3px"]);
                }
                ?>
                
                <?= ''//Html::a('ICT Professional Certification', ['professional/personal-information/my-profile'], ['class' => 'btn btn-danger', 'style' =>"margin-top:3px"]) ?><br>
                <?php else: ?>
                <h2>Applications</h2>
                <?= Html::a('Supplier/Company', ['/application/index'], ['class' => 'btn btn-danger', 'style' =>"margin-top:3px"]) ?><br>
                <?= ''//Html::a('ICT Professionals ', ['/professional/application/index'], ['class' => 'btn btn-danger', 'style' =>"margin-top:3px"]) ?><br>
                <?php endif; ?>
                <?= Html::a('Validate Certificate ', ['/site/validate'], ['class' => 'btn btn-primary', 'style' =>"margin-top:3px"]) ?>
            </div>
            
            <div class="col-lg-4 col-md-4" style="border-left: 1px solid red; height:100%">
                <!-- <h2>Bank Details</h2>
                Account Name: <strong>CITIBANK</strong><br/>
                Bank Name: <strong>ICT Authority</strong><br/>
                Account Number: <strong>0300085016</strong><br/>
                Branch: <strong>Upper Hill (code: 16000)</strong><br/>
                SWIFT Code: <strong>CITIKENA</strong><br>&nbsp;-->
                <h2>User Guide</h2>
                <p><?= Html::a('Click here to open user guide', ['/files/supaccreditationuserguide.pdf']) ?></p>
            </div>
            
            <div class="col-lg-4 col-md-4" style="border-left: 1px solid red;">
                <h2>Contacts</h2>
                Use the contacts below to reach us in case you experience difficulties using the site.<br/>
                <strong>Telephone Contacts:</strong><br/>
                    &nbsp;&nbsp;&nbsp;&nbsp;+254 20 2211960<br/>
                    &nbsp;&nbsp;&nbsp;&nbsp;+254 20 2211961<br/>
                Email: <a href="mailto:standards@ict.go.ke" target="_top">standards@ict.go.ke</a>
            </div>
        </div>
        <div class='row' style="border-top: 1px solid red; padding-top: 30px">
            <p>For detailed information on accreditation refer to the 
                <a href="http://icta.go.ke/standards/it-governance-standard/" target="_blank">IT Governance Standard Appendix 33 and 34</a></p>
        </div>
    </div>
</div>

