<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\professional\models\Approval */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="approval-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'status')->dropDownList([1 => 'Approved', 2 => 'Rejected'], ['prompt' => '']) ?>
    
    <?= $form->field($model, 'level')->dropDownList([1 => 'Secretariat', 2 => 'Committee'], ['prompt' => '']) ?>
    
    <?= $form->field($model, 'comment')->textarea(['rows' =>3]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'onclick'=>'saveDataForm(this); return false;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
