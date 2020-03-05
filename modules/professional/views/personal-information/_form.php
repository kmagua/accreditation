<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\professional\models\PersonalInformation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="personal-information-form">

    <?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>
    <div class="row"> 
        <div class="col-md-6">
            <?= $form->field($model, 'idno')->textInput(['maxlength' => true]) ?>
        </div>
        
        <div class="col-md-6">
            <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row"> 
        <div class="col-md-6">
            <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
        </div>
        
        <div class="col-md-6">
            <?= $form->field($model, 'other_names')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row"> 
        <div class="col-md-6">
            <?= $form->field($model, 'date_of_birth')->widget(\yii\jui\DatePicker::classname(), [
                //'language' => 'ru',
                'dateFormat' => 'dd-MM-yyyy',
                'clientOptions'=>[
                    'yearRange'=>(date('Y')-100).":".(date('Y') - 14),
                    'maxDate' => "+0d",
                    'changeYear' => true,
                    'changeMonth' => true
                ],
                'options' =>[
                    'class' => 'form-control',
                ]
            ]) ?>
        </div>
        
        <div class="col-md-6">
            <?= $form->field($model, 'gender')->dropDownList([ 'Male' => 'Male', 'Female' => 'Female', 'Transgender' => 'Transgender', ], ['prompt' => '']) ?>
        </div>
    </div>

    <div class="row"> 
        <div class="col-md-6">
            <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
        </div>
        
        <div class="col-md-6">
            <?= $form->field($model, 'nationality')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row"> 
        <div class="col-md-6">
            <?= $form->field($model, 'county')->textInput(['maxlength' => true]) ?>
        </div>
        
        <div class="col-md-6">
            <?= $form->field($model, 'postal_address')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="form-group" style='padding-left:150px'>
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
