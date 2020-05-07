<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\professional\models\Reference */
/* @var $form yii\widgets\ActiveForm */
?>

<?php if(Yii::$app->session->hasFlash('reference_added')): ?>
    <div class="alert alert-success alert-dismissable">
        <h4><?php echo Yii::$app->session->getFlash('reference_added'); ?></h4>
    </div>
<?php endif; ?> 

<div class="reference-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'type')->dropDownList([ 'Employer' => 'Employer', 'Other' => 'Other', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'upload_file')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 
            'onclick'=>'saveDataForm(this); return false;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
