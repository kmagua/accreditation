<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyStaffSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="company-staff-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'company_id') ?>

    <?= $form->field($model, 'first_name') ?>

    <?= $form->field($model, 'last_name') ?>

    <?= $form->field($model, 'national_id') ?>

    <?php // echo $form->field($model, 'kra_pin') ?>

    <?php // echo $form->field($model, 'gender') ?>

    <?php // echo $form->field($model, 'dob') ?>

    <?php // echo $form->field($model, 'disability_status') ?>

    <?php // echo $form->field($model, 'title') ?>

    <?php // echo $form->field($model, 'staff_type') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'date_created') ?>

    <?php // echo $form->field($model, 'last_updated') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
