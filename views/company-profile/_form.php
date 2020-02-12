<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyProfile */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="company-profile-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <div class="row"> 
        <div class="col-md-6">
            <?= $form->field($model, 'business_reg_no')->textInput(['maxlength' => true]) ?>
        </div>
           <div class="col-md-6">
               <?= $form->field($model, 'company_name')->textInput(['maxlength' => true]) ?>
        </div>        
    </div>
    
 
    
    <div class="row">
        <div class="col-md-6">
             <?= $form->field($model, 'registration_date')->textInput() ?>  
        </div>
        
        <div class="col-md-6">
            <?= $form->field($model, 'county')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
  

    <div class="row">
        <div class="col-md-6">
                <?= $form->field($model, 'town')->textInput(['maxlength' => true]) ?>
        </div>
        
        <div class="col-md-6">
             <?= $form->field($model, 'building')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'floor')->textInput(['maxlength' => true]) ?>
        </div>
        
        <div class="col-md-6">
               <?= $form->field($model, 'telephone_number')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    
        <div class="row">
        <div class="col-md-6">
              <?= $form->field($model, 'company_email')->textInput(['maxlength' => true]) ?>
        </div>
        
        <div class="col-md-6">
              <?= $form->field($model, 'type_of_business')->dropDownList([ 'Sole proprietor' => 'Sole proprietor', 'Partnership' => 'Partnership', 'Corporation' => 'Corporation', 'Private Company' => 'Private Company', 'Limited company' => 'Limited company', 'Co-operative' => 'Co-operative', ], ['prompt' => '']) ?>
        </div>
    </div>

        <div class="row">
        <div class="col-md-6">
             <?= $form->field($model, 'postal_address')->textInput(['maxlength' => true]) ?>
        </div>
        
        <div class="col-md-6">
             <?= $form->field($model, 'company_categorization')->dropDownList([ 'Open' => 'Open', 'Youth' => 'Youth', 'Women' => 'Women', 'People With Disability' => 'People With Disability', ], ['prompt' => '']) ?>
        </div>
    </div>

   
<!--

    <?=/** $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'date_created')->textInput() ?>

    <?= $form->field($model, 'last_updated')->textInput() */ ""?>
-->

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
