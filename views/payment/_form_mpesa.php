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
            'action' => ['application/lipa-na-mpesa', 'id'=>$model->application_id, 'l'=>$model->level],
            'options' => ['enctype' => 'multipart/form-data'
        ]]); ?>
    
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'billable_amount')->textInput(['readonly'=>true]) ?>
        </div>        
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'phone_number')->textInput() ?>
        </div>
        
        <div class="col-md-6">
            <?= $form->field($model, 'confirm_phone_number')->textInput() ?>
        </div>        
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'onclick'=>'saveDataForm(this); return false;']) ?>
        <?php if($model->mpesa_error_message != ''){ ?> 
        <h4 style="color:red"><?= $model->mpesa_error_message ?>. Perhaps try again later!</h4>
        <?php } ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
