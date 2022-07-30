<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="container" style="margin-top:2%;background-color:white;margin-left:-5%" id="Log">
    <div class="col-md-6 col-md-offset-3" style="background-color:white;border:solid 1px  #009933;-moz-border-radius:8px;-webkit-border-radius:8px;border-radius:8px;">
        <!--<div style="text-align:center;"><img src="<?= Yii::getAlias("@web") ?>/images/icta.png" height="60"; width="100" /></div>
        <br />
    
        <div class="row">
            <h3 class="text-center" style="background-color: red;width:100%;color:white;height:8%;text-align:center;margin-top:-15.5px;
            font-family:'Times New Roman', 'Times', Arial, sans-serif;font-weight:100">
                ICT Authority Supplier Accreditation System
            </h3>
        </div>
        <br>-->
        <div class="row">
            <!--<h1 class="text-center" style=width:100%;color:#000000;height:6%;text-align:center;margin-top:-15.5px;text-justify: 
            font-family:'Times New Roman', 'Times', Arial, sans-serif;font-weight:100">
                Register Account
            </h1>-->
        </div>
        <!--<br /> -->
<div class="user-form" style="color: black !important">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= ''//$form->field($model, 'kra_pin_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true])->label("Company Name") ?>

    <?= ''//$form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'password_repeat')->passwordInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'captcha')->widget(\yii\captcha\Captcha::classname(), [
        // configure additional widget properties here
    ]) ?>

    <div class="form-group">
        <div class="col-md-6">
       <?= Html::submitButton('Register', ['class' => 'btn btn-danger', 'style'=>'background-color:#CB3720']) ?>
        </div>
        <div class="col-md-5 col-md-offset-1">
            <?= Html::a('Back to Login', ['/site/login'], ['class'=>'btn btn-default', 'style'=>'background-color:#24537F; color:#fff']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
    </div>
</div>
