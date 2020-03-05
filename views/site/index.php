<?php

use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = 'ICT Authority Accreditation System';
?>
<br/><br/><br/>
<div class="row">
    <?= $this->title ?>
</div>
<div class="site-index">

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                
                <?php if(!Yii::$app->user->isGuest): ?>
                <h2>Choose Your action Below</h2>
                <?= Html::a('Company Accreditation', ['company-profile/my-companies'], ['class' => 'btn btn-success']) ?><br><br>
                <?= Html::a('Individual Accreditation', ['professional/personal-information/my-profile'], ['class' => 'btn btn-success']) ?>
                <?php else: ?>
                <p>Login to see you compmnies or register a new one!</p>
                <?php endif; ?>
            </div>
            <div class="col-lg-8">
                <h2 style="color: #c11d35">Supplier Accreditation Requirements</h2>
                <h5 style="font-style: italic"><b>The following documents are required during Accreditation</b></h5>

                <ol >
                    <li>  Duly Filled Form ICTA/STD/CTR/F001 Download Here</li>
                   <li>  Company profile</li>
                   <li>  Certificate of incorporation</li>
                   <li>  Companies act/ permit</li>
                   <li>  KRA compliance certificate</li>
                   <li>  CVs , IT related university certificate, project management certificate national id copies and KRA pin for all of all directors</li>
                   <li>  CVs, IT related degree, professional certifications, certification in project management for all technical staff</li>
                   <li>  Past LPOs and Recommendation Letters</li>
                   <li>  Recent bank statement from the last financial year together with the audited accounts of the same</li>
                   <li>  Partnership certificates if any</li>
                   <li>  Binded document to be sent to Teleposta Towers, 23rd Floor, ICTA Standards Department</li>
                   <li>  Accreditation Period is within one week of document receipt</li>
                    <li> Certificate Should Be Handpicked by a designated company contact person after a week grace period</li>
                   
                </ol>


                
            </div>
        </div>

    </div>
</div>
