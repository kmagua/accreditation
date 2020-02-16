<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProfessionalCertification */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="professional-certification-form">

    <?php $form = ActiveForm::begin([
            'id' =>'professional-certification-form',
            'action' => ($model->isNewRecord)?['professional-certification/create-ajax', 'sid'=>$model->staff_id] : 
                ['professional-certification/update-ajax', 'id'=>$model->id],
            'options' => ['enctype' => 'multipart/form-data']
        ]); ?>
    <div class="row"> 
        <div class="col-md-6">
            <?= $form->field($model, 'qualification_type')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'other_description')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row"> 
        <div class="col-md-8">
        <?= $form->field($model, 'certificate_upload')->fileInput() ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'onclick'=>'saveDataForm(this); return false;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
