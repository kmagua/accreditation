<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\IctaCommitteeMember */
/* @var $form yii\widgets\ActiveForm */
$this->title = "Members";
$data = \app\models\IctaCommitteeMember::findCommitteeMembersArray($level);
?>

<div class="document-type-form" style = 'margin-top: 50px'>

    <?php $form = ActiveForm::begin(['id' =>'icta-committee-member-form',
            'action' => ['application/committee-members', 'id' => $model->application_id, 'l' => $level]]); ?>
    
    <?= $form->field($model, 'committee_member_ids')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(\app\models\IctaCommitteeMember::findCommitteeMembersArray($level),'id','full_name'),
            'options' => ['placeholder' => 'Select a state ...', 'multiple'=>'true'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
