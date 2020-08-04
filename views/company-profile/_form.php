<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyProfile */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="company-profile-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'company_name')->textInput(['maxlength' => true, 
                'value' => Yii::$app->user->identity->full_name, 'readonly' => true]) ?>
        </div> 
        <div class="col-md-6">
            <?= $form->field($model, 'business_reg_no')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    
 
    
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'registration_date')->widget(\yii\jui\DatePicker::classname(), [
                //'language' => 'ru',
                'dateFormat' => 'dd-MM-yyyy',
                'clientOptions'=>[
                    'yearRange'=>(date('Y')-100).":".(date('Y')),
                    'maxDate' => "+0d",
                    'changeYear' => true,
                    'changeMonth' => 'true'
                ],
                'options' =>[
                    'class' => 'form-control',
                ]
            ]) ?>
        </div>
        
        <div class="col-md-6">
            <?= $form->field($model, 'county')->dropDownList([
                'Mombasa' => 'Mombasa', 'Kwale' => 'Kwale', 'Kilifi' => 'Kilifi',
'Tana' => 'Tana', 'Lamu' => 'Lamu', 'Taita-Taveta' => 'Taita-Taveta', 'Garissa' => 'Garissa',
'Wajir' => 'Wajir', 'Mandera' => 'Mandera', 'Marsabit' => 'Marsabit', 'Isiolo' => 'Isiolo',
'Meru' => 'Meru', 'Tharaka-Nithi' => 'Tharaka-Nithi', 'Embu' => 'Embu', 'Kitui' => 'Kitui',
'Machakos' => 'Machakos', 'Makueni' => 'Makueni', 'Nyandarua' => 'Nyandarua', 'Nyeri' => 'Nyeri',
'Kirinyaga'=> 'Kirinyaga', "Murang'a" => "Murang'a", 'Kiambu' => 'Kiambu', 'Turkana' => 'Turkana',
'West Pokot' => 'West Pokot', 'Samburu' => 'Samburu', 'Trans Nzoia' => 'Trans Nzoia',
'Uasin Gishu' => 'Uasin Gishu', 'Elgeyo-Marakwet' => 'Elgeyo-Marakwet', 'Nandi' => 'Nandi',
'Baringo' => 'Baringo', 'Laikipia' => 'Laikipia', 'Nakuru' => 'Nakuru', 'Narok' => 'Narok',
'Kajiado' => 'Kajiado', 'Kericho'=> 'Kericho', 'Bomet' => 'Bomet','Kakamega' => 'Kakamega',
'Vihiga' => 'Vihiga', 'Bungoma' => 'Bungoma', 'Busia' => 'Busia','Siaya' => 'Siaya',
'Kisumu' => 'Kisumu', 'Homa Bay' => 'Homa Bay','Migori' => 'Migori', 'Kisii' => 'Kisii',
'Nyamira' => 'Nyamira',
'Nairobi' => 'Nairobi'
], ['prompt' =>'']) ?>
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
            <?= $form->field($model, 'company_email')->textInput(['maxlength' => true, 
                'value' => Yii::$app->user->identity->username, 'readonly' => true]) ?>
        </div>
        
        <div class="col-md-6">
            <?= $form->field($model, 'company_type_id')->dropDownList(ArrayHelper::map(
                \app\models\CompanyType::find()->where("id > 0")->all(), 'id', 'name'), ['prompt' => '']) ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'turnover')->textInput() ?>
        </div>
        
        <div class="col-md-6">
            <?= $form->field($model, 'cashflow')->textInput() ?>
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
