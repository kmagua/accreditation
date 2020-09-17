<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PreSystemAccreditedCompanies */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pre-system-accredited-companies-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cert_reference')->textInput() ?>

    <?= $form->field($model, 'company_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_of_accreditation')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'valid_till')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'service_category')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'to_go')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'icta_grade')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
