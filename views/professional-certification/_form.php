<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProfessionalCertification */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="professional-certification-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'staff_id')->textInput() ?>

    <?= $form->field($model, 'qualification_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'other_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'certificate')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_created')->textInput() ?>

    <?= $form->field($model, 'last_updated')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
