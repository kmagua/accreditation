<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\StaffExperience */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="staff-experience-form">
    <?php if(Yii::$app->session->hasFlash('se_added')): ?>
    <div class="alert alert-danger alert-dismissable">
        <h4><?php echo Yii::$app->session->getFlash('se_added'); ?></h4>
    </div>
    <?php endif; ?>
    

    <?php $form = ActiveForm::begin([
            'id' =>'staff-experience-form',
            'action' => ($model->isNewRecord)?['staff-experience/create-ajax', 'sid'=>$model->staff_id] : 
                ['staff-experience/update-ajax', 'id'=>$model->id],
            //'options' => ['enctype' => 'multipart/form-data']
        ]); ?>
    
    <div class="row"> 
        <div class="col-md-6">
            <?= $form->field($model, 'organization')->textInput() ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'role')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    
    <div class="row"> 
        <div class="col-md-6">
             <?= $form->field($model, 'start_date')->widget(\yii\jui\DatePicker::classname(), [
                //'language' => 'ru',
                'dateFormat' => 'dd-MM-yyyy',
                'clientOptions'=>[
                    'yearRange'=>(date('Y')-70).":".(date('Y')),
                    'maxDate' => "+0day",
                    'changeYear' => true,
                    'changeMonth' => 'true'
                ],
                'options' =>[
                    'class' => 'form-control',
                ]
            ]) ?>
        </div>
        
        <div class="col-md-6">
             <?= $form->field($model, 'end_date')->widget(\yii\jui\DatePicker::classname(), [
                //'language' => 'ru',
                'dateFormat' => 'dd-MM-yyyy',
                'clientOptions'=>[
                    'yearRange'=>(date('Y')-70).":".(date('Y')+5),
                    //'maxDate' => "18Y",
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
        <div class="col-md-8">
            <?= $form->field($model, 'assignment')->textarea(['rows' => 4, 'cols'=>70, 'maxlength' => true]) ?>
        </div>        
    </div>
    max 200 characters<br>
    
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'onclick'=>'saveDataForm(this); return false;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
