<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyStaff */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="company-staff-form">
    <?php if(Yii::$app->session->hasFlash('staff_added')): ?>
    <div class="alert alert-success alert-dismissable">
        <h4><?php echo Yii::$app->session->getFlash('staff_added'); ?></h4>
    </div>
    <?php endif; ?> 
    
    <?php $form = ActiveForm::begin([
        'id' => 'staff-form',
        //'options' => ['class' => 'form-horizontal'],
    ]); ?>
    <div class="row"> 
        <div class="col-md-6">
            <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
        </div>
    
        <div class="col-md-6">
            <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row"> 
        <div class="col-md-6">
            <?= $form->field($model, 'national_id')->textInput() ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'kra_pin')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row"> 
        <div class="col-md-6">
            <?= $form->field($model, 'gender')->dropDownList([ 'Male' => 'Male', 'Female' => 'Female', ], ['prompt' => '']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'dob')->widget(\yii\jui\DatePicker::classname(), [
                //'language' => 'ru',
                'dateFormat' => 'dd-MM-yyyy',
                'clientOptions'=>[
                    'yearRange'=>(date('Y')-100).":".(date('Y')-18),
                    'maxDate' => "18Y",
                    'changeYear' => true,
                    'changeMonth' => 'true'
                ],
                'options' =>[
                    'class' => 'form-control',
                ]
            ]) ?>
        </div>
    </div>
    
    <div class="row"> 
        <div class="col-md-6">
            <?= $form->field($model, 'disability_status')->dropDownList([ 'Yes' => 'Yes', 'No' => 'No', ], ['prompt' => '']) ?>
        </div> 
        <div class="col-md-6">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    
    <div class="row"> 
        <div class="col-md-6">
            <?= $form->field($model, 'staff_type')->dropDownList([ 'Staff' => 'Staff', 
                'Technical Director' => 'Technical Director', 'Director' => 'Director', ], ['prompt' => '']) ?>
        </div>
    
        <div class="col-md-6">
            <?= $form->field($model, 'status')->dropDownList([ 1 => 'Active', 0 => 'Inactive', ], ['prompt' => '']) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'onclick'=>'saveDataForm(this); return false;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
