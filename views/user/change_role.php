<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Role Change';
/* @var $this yii\web\View */
/* @var $model app\models\ScoreItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'role')->dropDownList([ 'Admin' => 'Admin',
        'Secretariat' => 'Secretariat', 'Committee member' => 'Committee member',
        'Applicant' => 'Applicant']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
