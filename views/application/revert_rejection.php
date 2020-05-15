<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

\kartik\select2\Select2Asset::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\Application */
/* @var $form yii\widgets\ActiveForm */
$this->title = "Application Renewal " . $model->company_id;
$comp_exp_data = ArrayHelper::map(\app\models\CompanyExperience::find()->where(['company_id'=>$model->company_id])->all(), 'id', 'project_name');
$expression = new yii\db\Expression("id, concat_ws(' ', first_name, last_name) first_name");
$staff_data = ArrayHelper::map(\app\models\CompanyStaff::find()->select($expression)->
    where(['company_id'=>$model->company_id])->all(), 'id', 'first_name');

?>
<div class="application-create">

    <?php if(Yii::$app->session->hasFlash('members_added')): ?>
    <div class="alert alert-success alert-dismissable">
        <h4><?= Yii::$app->session->getFlash('members_added'); ?></h4>
    </div>
    <?php endif; ?> 

    <div class="application-form">

        <?php $form = ActiveForm::begin([
                'id' =>'revert-rejection-form',
            ]); ?>
        <?= $form->errorSummary($model); ?>

        <div class="row" style="padding-left:20px;"> 
            <?= $form->field($model, 'revert_rejection', ['options' => 
                ['tag' => 'span'],'template' => "{input}"])->checkbox(['checked' => false, 'required' => true])
                    ->label("I confirm that issues raised in the rejection comment have now been addressed.") ?>

        </div>

        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-success', 'onclick'=>'saveDataForm(this); return false;']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>