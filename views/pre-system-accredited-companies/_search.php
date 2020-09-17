<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PreSystemAccreditedCompaniesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pre-system-accredited-companies-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'cert_reference') ?>

    <?= $form->field($model, 'company_name') ?>

    <?= $form->field($model, 'date_of_accreditation') ?>

    <?= $form->field($model, 'valid_till') ?>

    <?php // echo $form->field($model, 'service_category') ?>

    <?php // echo $form->field($model, 'to_go') ?>

    <?php // echo $form->field($model, 'icta_grade') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
