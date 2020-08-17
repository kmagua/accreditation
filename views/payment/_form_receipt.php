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
        <div class="col-md-8">
            <?= $form->field($model, 'upload_file')->fileInput()
                ->label("Upload a receipt [issued by Finance Department - ICTA] for payment of KES: " . $model->billable_amount) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'onclick'=>'saveDataForm(this); return false;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
