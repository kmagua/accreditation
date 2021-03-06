<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\IctaCommitteeMember */
/* @var $form yii\widgets\ActiveForm */
$this->title = "Members";
//$data = \app\models\IctaCommitteeMember::findCommitteeMembersArray($level);
?>

<div class="document-type-form">
    
    <?php if(Yii::$app->session->hasFlash('members_added')): ?>
    <div class="alert alert-success alert-dismissable">
        <h4><?= Yii::$app->session->getFlash('members_added'); ?></h4>
    </div>
    <?php endif; ?>
    
    <?php $form = ActiveForm::begin(['id' =>'icta-committee-member-form',
            'action' => ['application/committee-members', 'id' => $model->application_id, 'l' => $level]]); ?>
    
    <?= $form->field($model, 'committee_member_ids')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(\app\models\IctaCommitteeMember::findCommitteeMembersArray($level),'id','full_name'),
            'options' => ['placeholder' => 'Select a member(s) ...', 'multiple'=>'true'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label(($level ==1)?'Secretariat Member(s)':'Committee Member(s)');
    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'onclick'=>'saveDataForm(this); return false;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
