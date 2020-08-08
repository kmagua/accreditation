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
        
        <?= Html::label('Enter your E-Mail Address', 'kra_pin_number') ?>

        <?= Html::textInput('kra_pin_number', '', ['id' => 'kra_pin_number', 
            'class'=>'form-control', 'style' =>'margin-bottom:10px']) ?>

        <div class="form-group">
            <div class="col-md-6">
           <?= Html::submitButton('Reset Password', ['class' => 'btn btn-success']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>    
</div>
