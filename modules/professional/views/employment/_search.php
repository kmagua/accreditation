<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\professional\models\EmploymentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'organisation_name') ?>

    <?= $form->field($model, 'organisation_email') ?>

    <?= $form->field($model, 'organisation_phone') ?>

    <?= $form->field($model, 'job_title') ?>

    <?php // echo $form->field($model, 'role') ?>

    <?php // echo $form->field($model, 'postal_address') ?>

    <?php // echo $form->field($model, 'website') ?>

    <?php // echo $form->field($model, 'supervisor_name') ?>

    <?php // echo $form->field($model, 'supervisor_designation') ?>

    <?php // echo $form->field($model, 'supervisor_email') ?>

    <?php // echo $form->field($model, 'supervisor_phone') ?>

    <?php // echo $form->field($model, 'date_created') ?>

    <?php // echo $form->field($model, 'date_modified') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
