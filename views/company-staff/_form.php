<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyStaff */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="company-staff-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'company_id')->textInput() ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'national_id')->textInput() ?>

    <?= $form->field($model, 'kra_pin')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gender')->dropDownList([ 'Male' => 'Male', 'Female' => 'Female', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'dob')->textInput() ?>

    <?= $form->field($model, 'disability_status')->dropDownList([ 'Yes' => 'Yes', 'No' => 'No', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'staff_type')->dropDownList([ 'Staff' => 'Staff', 'Technical Director' => 'Technical Director', 'Director' => 'Director', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'status')->dropDownList([ 1 => '1', 0 => '0', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'date_created')->textInput() ?>

    <?= $form->field($model, 'last_updated')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
