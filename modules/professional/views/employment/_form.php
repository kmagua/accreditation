<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

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

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
        <?= $form->field($model, 'organisation_name')->textInput(['maxlength' => true, 
            'class'=>"col-md-5 col-lg-5 col-sm-offset-1 form-control"])->input('organisation_name', ['placeholder' => "Organization Name"])->label(false) ?>
        </div>
        <div class="col-md-6">
        <?= $form->field($model, 'organisation_email')->textInput(['maxlength' => true,
                'class'=>"col-md-5 col-lg-5 col-sm-offset-1 form-control"])->input('organisation_email', ['placeholder' => "Organization Email"])->label(false) ?>
        </div>
    </div>
        
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'organisation_phone')->textInput(['maxlength' => true,
                'class'=>"col-md-5 col-lg-5 col-sm-offset-1 form-control"])->input('organisation_phone', ['placeholder' => "Organization Phone"])->label(false) ?>
        </div>
        <div class="col-md-6">
        <?= $form->field($model, 'job_title')->textInput(['maxlength' => true,
                'class'=>"col-md-5 col-lg-5 col-sm-offset-1 form-control"])->input('job_title', ['placeholder' => "Job Title"])->label(false) ?>
        </div>
    </div>
   
    <?= $form->field($model, 'role')->textarea(['rows' => 6]) ?>   
        
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'postal_address')->textInput(['maxlength' => true,
                'class'=>"col-md-5 col-lg-5 col-sm-offset-1 form-control"])->input('postal_address', ['placeholder' => "Postal Address"])->label(false) ?>
        </div>
        <div class="col-md-6">
        <?= $form->field($model, 'website')->textInput(['maxlength' => true,
                'class'=>"col-md-5 col-lg-5 col-sm-offset-1 form-control"])->input('website', ['placeholder' => "Website"])->label(false) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'supervisor_name')->textInput(['maxlength' => true,
                'class'=>"col-md-5 col-lg-5 col-sm-offset-1 form-control"])->input('supervisor_name', ['placeholder' => "Supervisor Name"])->label(false) ?>
        </div>
        <div class="col-md-6">
        <?= $form->field($model, 'supervisor_designation')->textInput(['maxlength' => true,
                'class'=>"col-md-5 col-lg-5 col-sm-offset-1 form-control"])->input('supervisor_designation', ['placeholder' => "Supervisor Designation"])->label(false) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'supervisor_email')->textInput(['maxlength' => true,
                'class'=>"col-md-5 col-lg-5 col-sm-offset-1 form-control"])->input('supervisor_email', ['placeholder' => "Supervisor Email"])->label(false) ?>
        </div>
        <div class="col-md-6">
        <?= $form->field($model, 'supervisor_phone')->textInput(['maxlength' => true,
                'class'=>"col-md-5 col-lg-5 col-sm-offset-1 form-control"])->input('supervisor_phone', ['placeholder' => "Supervisor Phone"])->label(false) ?>
        </div>
    </div>

    <div class="form-group" style='padding-left: 150px'>
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 
            'onclick'=>'saveDataForm(this); return false;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
