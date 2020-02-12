<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AcademicQualification */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="academic-qualification-form">

    <?php
        $form = ActiveForm::begin([
            'id' =>'academic-qualification-form',
            'action' => ($model->isNewRecord)?['academic-qualification/create-ajax', 'sid'=>$model->staff_id] : 
                ['academic-qualification/update-ajax', 'id'=>$model->id],
            'options' => ['enctype' => 'multipart/form-data'
        ]]); 
    ?>
    
    <div class="row"> 
        <div class="col-md-6">
        <?= $form->field($model, 'level')->dropDownList([ 'Diploma' => 'Diploma', 'Higher Diploma' => 'Higher Diploma',
            'Bachelor' => 'Bachelor', 'Masters' => 'Masters', 'PhD' => 'PhD', 'Certificate' => 'Certificate']
                , ['prompt' => '']) ?>
        </div>
        
        <div class="col-md-6">
            <?= $form->field($model, 'course_name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    
    <div class="row"> 
        <div class="col-md-6">
            <?= $form->field($model, 'certificate_upload')->fileInput() ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'onclick'=>'saveStaffData(this); return false;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
