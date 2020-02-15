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

$this->registerCss("
    .select2-container--open{
        z-index:9999999         
    }
    ");
?>

<div class="application-form">

    <?php $form = ActiveForm::begin([
            'id' =>'application-form',
        ]); ?>
    <?= $form->errorSummary($model); ?>
    
    <div class="row"> 
        <div class="col-md-6">
            <?= $form->field($model, 'accediation_category_id')->dropDownList(
             ArrayHelper::map(app\models\AccreditationType::find()->all(), 'id', 'name'), ['prompt'=>'']) ?>
        </div>
        
        <div class="col-md-6">
            <?= $form->field($model, 'financial_status_amount')->textInput() ?>
        </div>
    </div>

    <div class="row"> 
        <div class="col-md-6">
            <?= $form->field($model, 'financial_status_link')->textInput(['maxlength' => true]) ?>
        </div>
        
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
    </div>
    
    <div class="row"> 
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
        
        <div class="col-md-6">
            <?= $form->field($model, 'declaration', ['options' => 
                ['tag' => 'span'],'template' => "{input}"])->checkbox(['checked' => false, 'required' => true])
                    ->label("I declare that the information given here is correct to the best of my knowledge") ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-success', 'onclick'=>'saveStaffData(this); return false;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$js = <<<JS
       
    
JS;
$this->registerJs($js,yii\web\View::POS_END, 'enable_select2');
?>
