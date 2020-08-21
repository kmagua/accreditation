<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Changing My Password';
?>



<div class="user-form">
    <h3><?= $this->title ?></h3>
    <?php $form = ActiveForm::begin(); ?>    

    <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'readonly'=> true])->label('Email') ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'password_repeat')->passwordInput(['maxlength' => true]) ?>
    

    <div class="form-group">
        <div class="col-md-6">
       <?= Html::submitButton('Register', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
