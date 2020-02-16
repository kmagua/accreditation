<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ScoreItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="score-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'category')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'specific_item')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'score_item')->textarea(['rows' => 5]) ?>

    <?= $form->field($model, 'maximum_score')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
