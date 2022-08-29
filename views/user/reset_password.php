<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
$this->title = "Reset Password";
?>

<div style="margin-top:2%;background-color:white;" id="Log">
    <div class="user-password-reset-form">

        <?php $form = ActiveForm::begin(); ?>  
        <?= $form->field($model, 'email')->textInput()->label('Enter your E-Mail Address') ?>       
        
        <?= $form->field($model, 'reCaptcha')->widget(\kekaadrenalin\recaptcha3\ReCaptchaWidget::class) ?>

        <div class="form-group">
            <div class="col-md-6">
           <?= Html::submitButton('Reset Password', ['class' => 'btn btn-success', 'style' => 'background-color:#006638']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>    
</div>
