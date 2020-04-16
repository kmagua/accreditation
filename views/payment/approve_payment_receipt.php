<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Payment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payment-form">

    <?php $form = ActiveForm::begin([
            'id' =>'payment-approval-form',
            'action' => ['application/approve-payment', 'id'=>$model->application_id, 'l'=>$model->level],
            'options' => ['enctype' => 'multipart/form-data',
            'layout' => 'horizontal',
        ]]); ?>
    <p><?= $model->getReceipt(TRUE) ?></p>
    <div class="row"> 
        <div class="col-md-8">
            <h4>Applicable payment Amount KES: <?= $model->application->getPayableAtLevel(); ?></h4>
        </div>
    </div>
    
    <div class="row"> 
        <div class="col-md-8">
            <?= $form->field($model, 'status')->dropDownList(['confirmed'=>'Confirmed', 'rejected'=>'Rejected'], ['prompt'=>'']) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'onclick'=>'saveDataForm(this); return false;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
