<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Internal User Registration';
?>



<div class="user-form">
    <h3><?= $this->title ?></h3>
    <?php $form = ActiveForm::begin(); ?>    

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'role')->dropDownList([ 'Admin' => 'Admin',
        'Secretariat' => 'Secretariat', 'Committee member' => 'Committee member',
        'Applicant' => 'Applicant']) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'password_repeat')->passwordInput(['maxlength' => true]) ?>
    

    <div class="form-group">
        <div class="col-md-6">
       <?= Html::submitButton('Register', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
