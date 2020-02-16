<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Payment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payment-form">

    <?php $form = ActiveForm::begin([
            'id' =>'payment-form',
            'action' => ['application/upload-receipt', 'id'=>$model->application_id, 'l'=>$model->level],
            'options' => ['enctype' => 'multipart/form-data'
        ]]); ?>
    
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'billable_amount')->textInput(['readonly'=>true]) ?>
        </div>
    
        <div class="col-md-6">
            <?= $form->field($model, 'mpesa_code')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    
    <div class="row"> 
        <div class="col-md-6">
            <?= $form->field($model, 'upload_file')->fileInput() ?>
        </div>

    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'onclick'=>'saveDataForm(this); return false;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
