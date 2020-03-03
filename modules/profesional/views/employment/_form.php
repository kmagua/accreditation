<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\profesional\models\Employment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'organisation_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'organisation_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'organisation_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'job_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'role')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'postal_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'website')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'supervisor_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'supervisor_designation')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'supervisor_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'supervisor_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_created')->textInput() ?>

    <?= $form->field($model, 'date_modified')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
