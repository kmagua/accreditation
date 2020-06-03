<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\professional\models\RenewalCpd */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="renewal-cpd-form">
    <?php if(Yii::$app->session->hasFlash('cpd_added')): ?>
    <div class="alert alert-success alert-dismissable">
        <h4><?php echo Yii::$app->session->getFlash('cpd_added'); ?></h4>
    </div>
    <?php endif; ?>
    <?php $form = ActiveForm::begin(); ?>
    <div class="row"> 
        <div class="col-md-6">
            <?= $form->field($model, 'type')->dropDownList([
                'Attended a conference' => 'Attended a conference',
                'Attended a seminar' => 'Attended a seminar',
                'Training course' => 'Training course',
                'Presentation' => 'Presentation',
                'Journal Paper' => 'Journal Paper',
            ], ['prompt' => '']) ?>
        </div>
    
        <div class="col-md-6">
            <?= $form->field($model, 'description')->textarea(['maxlength' => true, 'rows' =>2]) ?>
        </div>
    </div>
    
    <div class="row"> 
        <div class="col-md-6">
            <?= $form->field($model, 'start_date')->widget(\yii\jui\DatePicker::classname(), [
                //'language' => 'ru',
                'dateFormat' => 'dd-MM-yyyy',
                'clientOptions' => [
                    'yearRange' => (date('Y')-3) . ":" . date('Y'),
                    'maxDate' => "+0d",
                    'changeYear' => true,
                    'changeMonth' => true
                ],
                'options' => [
                    'class' => 'form-control',
                    'readonly' => true
                ]
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'end_date')->widget(\yii\jui\DatePicker::classname(), [
                //'language' => 'ru',
                'dateFormat' => 'dd-MM-yyyy',
                'clientOptions' => [
                    'yearRange' => (date('Y')-3) . ":" . (date('Y') + 4),
                    'maxDate' => "+4y",
                    'changeYear' => true,
                    'changeMonth' => true
                ],
                'options' => [
                    'class' => 'form-control',
                    'readonly' => true
                ]
            ]) ?>
        </div>
    </div>

    <div class="row"> 
        <div class="col-md-6">
            <?= $form->field($model, 'upload_file')->fileInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="form-group">
         <?= Html::submitButton('Save', ['class' => 'btn btn-success', 
            'onclick'=>'saveDataForm(this); return false;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
