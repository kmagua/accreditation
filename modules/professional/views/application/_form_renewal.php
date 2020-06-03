<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\modules\professional\models\Application */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="application-form" id="individual_app_div">
    
    <?php $form = ActiveForm::begin(['layout'=>'horizontal', 
        'action' =>['application/update-renewal', 'id'=>$model->id],
            'options' => ['enctype' => 'multipart/form-data'
        ]]); ?>
    
    
    <?= $form->field($model, 'status')->hiddenInput(['value' => ''])->label(false) ?>
    
    <?= $form->field($model, 'declaration')->checkBox() ?>

    <div class="form-group" style="padding-left: 15px">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 
            'onclick'=>'saveDataForm(this); return false;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
