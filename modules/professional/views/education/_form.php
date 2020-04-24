<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\modules\professional\models\Education */
/* @var $form yii\widgets\ActiveForm */
?>
<?php if(Yii::$app->session->hasFlash('education_added')): ?>
    <div class="alert alert-success alert-dismissable">
        <h4><?php echo Yii::$app->session->getFlash('education_added'); ?></h4>
    </div>
<?php endif; ?>

<div class="education-form">

    <?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>

    <?= $form->field($model, 'level_id')->dropDownList(
        ArrayHelper::map(\app\modules\professional\models\EducationLevel::find()->all(), 'id', 'name'), 
            ['prompt' => '']) ?>

    <?= $form->field($model, 'course')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'institution')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'completion_date')->widget(\yii\jui\DatePicker::classname(), [
        //'language' => 'ru',
        'dateFormat' => 'dd-MM-yyyy',
        'clientOptions'=>[
            'yearRange'=>(date('Y')-100).":".(date('Y')+5),
            'maxDate' => "+5y",
            'changeYear' => true,
            'changeMonth' => 'true'
        ],
        'options' =>[
            'class' => 'form-control',
        ]
    ]) ?>
    
    <?= $form->field($model, 'upload_file')->fileInput() ?>

    <div class="form-group" style='padding-left:120px'>
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 
            'onclick'=>'saveDataForm(this); return false;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
