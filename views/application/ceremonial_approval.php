<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="application-create">

    <?php if(Yii::$app->session->hasFlash('members_added')): ?>
    <div class="alert alert-success alert-dismissable">
        <h4><?= Yii::$app->session->getFlash('members_added'); ?></h4>
    </div>
    <?php endif; ?> 

    <div class="application-form">

        <?php $form = ActiveForm::begin([
                'id' =>'ceremonial-approval-form',
            ]); ?>
        <?= $form->errorSummary($model); ?>

        <div class="row" style="padding-left:20px;"> 
            <?= $form->field($model, 'ceremonial_approval', ['options' => 
                ['tag' => 'span'],'template' => "{input}"])->checkbox(['checked' => false, 'required' => true])
                    ->label("I agree with the ratings for this application.") ?>

        </div>

        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-success', 'onclick'=>'saveDataForm(this); return false;']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>