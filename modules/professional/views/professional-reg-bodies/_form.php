<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\professional\models\ProfessionalRegBodies */
/* @var $form yii\widgets\ActiveForm */
?>
<?php if(Yii::$app->session->hasFlash('membership_added')): ?>
    <div class="alert alert-success alert-dismissable">
        <h4><?php echo Yii::$app->session->getFlash('membership_added'); ?></h4>
    </div>
<?php endif; ?> 

<div class="professional-reg-bodies-form">

    <?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'membership_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'upload_file')->fileInput(['maxlength' => true]) ?>

    <div class="form-group" style="padding-left: 120px">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 
            'onclick'=>'saveDataForm(this); return false;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
