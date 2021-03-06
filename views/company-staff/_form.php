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
            <?= ''//$form->field($model, 'kra_pin')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'gender')->dropDownList([ 'Male' => 'Male', 'Female' => 'Female', ], ['prompt' => '']) ?>
        </div>
    </div>

    <div class="row"> 
        <div class="col-md-6">
            <?= $form->field($model, 'dob')->widget(\yii\jui\DatePicker::classname(), [
                //'language' => 'ru',
                'dateFormat' => 'dd-MM-yyyy',
                'clientOptions'=>[
                    'yearRange'=>(date('Y')-100).":".(date('Y')-18),
                    'maxDate' => '-18year',
                    'changeYear' => true,
                    'changeMonth' => true,
                    'defaultDate' => '-18year',
                ],
                'options' =>[
                    'class' => 'form-control',
                    'readonly' => 'readonly'
                ]
            ]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'disability_status')->dropDownList([ 'No' => 'No', 'Yes' => 'Yes', ], ['prompt' => '']) ?>
            
            <?= '' //$form->field($model, 'dob')->textInput() ?>
        </div>
    </div>
    
    <div class="row"> 
        <div class="col-md-6">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div> 
        <div class="col-md-6">
            <?= $form->field($model, 'staff_type')->dropDownList([ 'Director' => 'Director', 
                'Technical Director' => 'Technical Director', 'Technical Staff' => 'Technical Staff', ], ['prompt' => '']) ?>
        </div>
    </div>
    
    <!--<div class="row"> 
        <div class="col-md-6">
            <?= ''//$form->field($model, 'status')->dropDownList([ 1 => 'Active', 0 => 'Inactive', ], ['prompt' => '']) ?>
        </div>
    
        <div class="col-md-6">
            
        </div>
    </div> -->

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'onclick'=>'saveDataForm(this); return false;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$year_range = (date('Y')-100).":".(date('Y'));
$js = <<<JS
$( document ).ready(function() {    
    $(document).unbind('focusin');
});
JS;
$this->registerJs($js,yii\web\View::POS_END, 'this_is_date_field');