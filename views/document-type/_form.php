<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\DocumentType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="document-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'status')->dropDownList([1 => 'Active', 0 => 'Inactive']) ?>    
    
    <?= $form->field($model, 'applicable_app_types')->widget(Select2::classname(), [
            'data' => \yii\helpers\ArrayHelper::map(app\models\CompanyType::find()->all(), 'id', 'name'),
            'language' => 'en',
            'options' => ['placeholder' => 'Select ..','multiple'=>true,],
            'pluginOptions' => [
                'allowClear' => true, 
                //'dropdownParent' => "#accreditation-modal", 
            ],
        ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
