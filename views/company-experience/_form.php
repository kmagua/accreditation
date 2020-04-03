<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyExperience */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="company-experience-form">
    <?php if(Yii::$app->session->hasFlash('ce_added')): ?>
    <div class="alert alert-success alert-dismissable">
        <h4><?php echo Yii::$app->session->getFlash('ce_added'); ?></h4>
    </div>
    <?php endif; ?> 
    <?php $form = ActiveForm::begin(['id' =>'company-experience-form',
        'action' => ($model->isNewRecord) ? ['company-experience/create-ajax', 'cid'=>$model->company_id] : 
            ['company-experience/update-ajax', 'id'=>$model->id],
            'options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="row"> 
        <div class="col-md-6">
            <?= $form->field($model, 'organization_type')->dropDownList([ 'Public' => 'Public', 'Private' => 'Private', ], ['prompt' => '']) ?>
        </div>
        
        <div class="col-md-6">
            <?= $form->field($model, 'project_name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    
    <div class="row"> 
        <div class="col-md-6">
             <?= $form->field($model, 'start_date')->widget(\yii\jui\DatePicker::classname(), [
                //'language' => 'ru',
                'dateFormat' => 'dd-MM-yyyy',
                'clientOptions'=>[
                    'yearRange'=>(date('Y')-10).":".(date('Y')),
                    'maxDate' => "+0day",
                    'changeYear' => true,
                    'changeMonth' => 'true'
                ],
                'options' =>[
                    'class' => 'form-control',
                    'readonly' => 'readonly'
                ]
            ]) ?>
        </div>
        <div class="col-md-6">
             <?= $form->field($model, 'end_date')->widget(\yii\jui\DatePicker::classname(), [
                //'language' => 'ru',
                'dateFormat' => 'dd-MM-yyyy',
                'clientOptions'=>[
                    'yearRange'=>(date('Y')-10).":".(date('Y')+5),
                    //'maxDate' => "18Y",
                    'changeYear' => true,
                    'changeMonth' => 'true'
                ],
                'options' =>[
                    'class' => 'form-control',
                    'readonly' => 'readonly'
                ]
            ]) ?>
        </div>
    </div>
    
    <div class="row"> 
        <div class="col-md-6">
        <?= $form->field($model, 'status')->dropDownList([ 'Ongoing' => 'Ongoing', 'Completed' => 'Completed', 'Suspended' => 'Suspended', 'Terminated' => 'Terminated', ], ['prompt' => '']) ?>
        </div>
    
        <div class="col-md-6">
            <?= $form->field($model, 'project_cost')->textInput() ?>
        </div>
    </div>
    
    <div class="row"> 
        <div class="col-md-8">
            <?= $form->field($model, 'upload_file')->fileInput() ?>
        </div>
    </div>
    <div class="form-group">
       <?= Html::submitButton('Save', ['class' => 'btn btn-success',
            'onclick'=>'saveDataForm(this); return false;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
