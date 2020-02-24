<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
$this->title = "Change Password";
?>


<div class="user-form">
    <h1><?= $this->title ?></h1>
    
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true, 'value' => '']) ?>
    
    <?= $form->field($model, 'password_repeat')->passwordInput(['maxlength' => true]) ?>

    <div class="form-group">
        <div class="col-md-6">
            <?= Html::submitButton('Chnage Password', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
