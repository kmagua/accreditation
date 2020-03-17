<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = "Login";
?>


<div class="container" style="margin-top:6%;background-color:white;margin-left:-2%" id="Log">
    <div class="col-md-5 col-md-offset-3 alert alert-info" style="background-color:white;border:solid 1px  #009933;-moz-border-radius:8px;-webkit-border-radius:8px;border-radius:8px;">
        <div style="text-align:center;"><img src="<?= Yii::getAlias('@web') ?>/images/icta.png" height="60"; width="100" /></div>
        <br />
    
        <div class="row">
            <h3 class="text-center" style="background-color: red;width:100%;color:white;height:8%;text-align:center;margin-top:-15.5px;
            font-family:'Times New Roman', 'Times', Arial, sans-serif;font-weight:100">
                ICT Authority Accreditation System
            </h3>
        </div>
        <br>
        <div class="row">
            <h4 class="text-center" style=width:100%;color:green;height:6%;text-align:center;margin-top:-15.5px;text-justify: 
            font-family:'Times New Roman', 'Times', Arial, sans-serif;font-weight:100">
                Login
            </h4>
        </div>
        <br />


<div class="site-login">
    <?php if(Yii::$app->session->hasFlash('user_confirmation')): ?>
    <div class="alert alert-success alert-dismissable">
        <h4><?php echo Yii::$app->session->getFlash('user_confirmation'); ?></h4>
    </div>
    <?php else: ?>    
   
    <?php endif; ?>
    <p>Please fill out the following fields to login:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        
    ]); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <?= $form->field($model, 'rememberMe')->checkbox([ ]) ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-1">
                <?= Html::submitButton('Login', ['class' => 'btn btn-success', 'name' => 'login-button']) ?>
            </div>
            <div class="col-lg-offset-1 col-lg-6">
                <span style="color: red">  New Member? </span>
                <?= Html::a('Register', ['/user/register'], ['class'=>'btn btn-default']) ?>          
            </div>
            <div class="col-lg-2">
                <span style="color: red">  Forgot Password? </span>
                <?= Html::a('Reset', ['/user/reset-password'], ['class'=>'btn btn-default']) ?>          
            </div>
         </div>
    


    <?php ActiveForm::end(); ?>


</div>
        
    </div>
</div>
