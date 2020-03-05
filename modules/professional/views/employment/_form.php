<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\professional\models\Employment */
/* @var $form yii\widgets\ActiveForm */
?>

<?php if(Yii::$app->session->hasFlash('employment_added')): ?>
    <div class="alert alert-success alert-dismissable">
        <h4><?php echo Yii::$app->session->getFlash('employment_added'); ?></h4>
    </div>
<?php endif; ?> 

<div class="employment-form">

    <?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>

    <?= $form->field($model, 'organisation_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'organisation_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'organisation_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'job_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'role')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'postal_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'website')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'supervisor_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'supervisor_designation')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'supervisor_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'supervisor_phone')->textInput(['maxlength' => true]) ?>

    <div class="form-group" style='padding-left: 150px'>
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 
            'onclick'=>'saveDataForm(this); return false;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
