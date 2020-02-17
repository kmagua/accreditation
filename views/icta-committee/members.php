<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\IctaCommitteeMember */
/* @var $form yii\widgets\ActiveForm */
$this->title = "Members";

$select = new \yii\db\Expression("id,concat_ws(' ', first_name, last_name) full_name");
$role = ($model->committee->id == 1)?"Secretariat":"Committee member";
?>

<div class="document-type-form">

    <?php $form = ActiveForm::begin(['id' =>'icta-committee-member-form',
            'action' => ['icta-committee/add-members', 'id'=>$model->committee_id] ]); ?>

    
    
    <?= $form->field($model, 'committee_members')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(\app\models\User::find()->where(['role' =>$role])->all(), 'id', 'full_name'),
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
