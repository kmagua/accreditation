<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

\kartik\select2\Select2Asset::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\Application */
/* @var $form yii\widgets\ActiveForm */
$comp_exp_data = ArrayHelper::map(\app\models\CompanyExperience::find()->where(['company_id'=>$model->company_id])->all(), 'id', 'project_name');
$expression = new yii\db\Expression("id, concat_ws(' ', first_name, last_name) first_name");
$staff_data = ArrayHelper::map(\app\models\CompanyStaff::find()->select($expression)->
    where(['company_id'=>$model->company_id])->all(), 'id', 'first_name');

?>

<div class="application-form">

    <?php $form = ActiveForm::begin([
            'id' =>'application-form',
        ]); ?>
    <?= $form->errorSummary($model); ?>
    
    <div class="row"> 
        <div class="col-md-6">
            <?= $form->field($model, 'accreditation_type_id')->dropDownList(
             ArrayHelper::map(app\models\AccreditationType::find()->all(), 'id', 'name'), ['prompt'=>'']) ?>
        </div>
        
        <div class="col-md-6">
            <?= $form->field($model, 'cash_flow')->textInput() ?>
        </div>
        
    </div>

    <div class="row"> 
        
         <div class="col-md-6">
            <?= $form->field($model, 'turnover')->textInput() ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'financial_status_link')->textInput(['maxlength' => true])
                ->label(null, ['data-toggle' => 'tooltip','data-placement' =>'right',
                    'title' => 'Upload the file somewhere like on your website/google drive and then provide the link to it.']) ?>
        </div>
        
     
    </div>
    
    <div class="row">
          <div class="col-md-6">
            <?= $form->field($model, 'app_company_experience')->widget(Select2::classname(), [
                    'data' => $comp_exp_data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Projects to include on application','multiple'=>true,],
                    'pluginOptions' => [
                        'allowClear' => true, 
                        //'dropdownParent' => "#accreditation-modal", 
                    ],
                ]);
            ?>
       
        </div> 
        
        <div class="col-md-6">
            <?= $form->field($model, 'app_staff')->widget(Select2::classname(), [
                    'data' => $staff_data,
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select Staff to include on application','multiple'=>true,],
                    'pluginOptions' => [
                        'allowClear' => true, 'multiple'=>true,
                        //'dropdownParent' => "#accreditation-modal", 
                    ],
                ]);
            ?>            
        </div>
    </div>
    
    <div class="row">
          <div class="col-md-6">
            <?= $form->field($model, 'application_type')->dropDownList([
                1 => 'Initial Application', 2=>'Annual Renewal'
            ], ['prompt'=>'', 'onchange' => 'if(this.value ==1){'
                . ' $("#application-previous_category").val("");'
                . ' $("#application-previous_category").prop("disabled", true); }'
                . 'else{ $("#application-previous_category").prop("disabled", false); }']) ?>
       
        </div> 
        
        <div class="col-md-6">
            <?= $form->field($model, 'previous_category')->dropDownList([                
                    'ICTA 1' => 'ICTA 1', 'ICTA 2' => 'ICTA 2', 'ICTA 3' => 'ICTA 3', 'ICTA 4' => 'ICTA 4',
                    'ICTA 5' => 'ICTA 5', 'ICTA 6' => 'ICTA 6', 'ICTA 7' => 'ICTA 7', 'ICTA 8' => 'ICTA 8'                    
                ],
                ['prompt' => '', 'disabled'=>$model->application_type ==1]);
            ?>            
        </div>
    </div>
    
    <div class="row">
         <div class="col-md-6">
            <?= $form->field($model, 'declaration', ['options' => 
                ['tag' => 'span'],'template' => "{input}"])->checkbox(['checked' => false, 'required' => true])
                    ->label("I declare that the information given here is correct to the best of my knowledge") ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-success', 'onclick'=>'saveDataForm(this); return false;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
