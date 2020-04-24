<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\professional\models\Payment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payment-form">

    <?php $form = ActiveForm::begin([
            'id' =>'payment-form',
            'action' => ['/professional/application/upload-receipt', 'id'=>$model->application_id],
            'options' => ['enctype' => 'multipart/form-data'
        ]]); ?>
    
    <div class="row"> 
        <div class="col-md-8">
            <?= $form->field($model, 'upload_file')->fileInput()
                ->label("Upload a receipt for payment of KES: " . $model->application->category->application_fee) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'onclick'=>'saveDataForm(this); return false;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
