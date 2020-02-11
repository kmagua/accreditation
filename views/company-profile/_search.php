<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyProfileSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="company-profile-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'business_reg_no') ?>

    <?= $form->field($model, 'company_name') ?>

    <?= $form->field($model, 'registration_date') ?>

    <?= $form->field($model, 'county') ?>

    <?php // echo $form->field($model, 'town') ?>

    <?php // echo $form->field($model, 'building') ?>

    <?php // echo $form->field($model, 'floor') ?>

    <?php // echo $form->field($model, 'telephone_number') ?>

    <?php // echo $form->field($model, 'company_email') ?>

    <?php // echo $form->field($model, 'type_of_business') ?>

    <?php // echo $form->field($model, 'postal_address') ?>

    <?php // echo $form->field($model, 'company_categorization') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <?php // echo $form->field($model, 'date_created') ?>

    <?php // echo $form->field($model, 'last_updated') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
