<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Role Change';
/* @var $this yii\web\View */
/* @var $model app\models\ScoreItem */
/* @var $form yii\widgets\ActiveForm */
$usr_roles = (Yii::$app->user->identity->isAdmin(false))?['Admin' => 'Admin',
    'Secretariat' => 'Secretariat', 'Committee member' => 'Committee member',
        'Applicant' => 'Applicant', 'SU' => 'SU', 'Director' => 'Director', 'Chair'=>'Chair Person', 'PDTP' => 'PDTP'] : ['Secretariat' => 'Secretariat',
            'Committee member' => 'Committee member', 'Applicant' => 'Applicant', 'SU' => 'SU', 'PDTP' => 'PDTP'];
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'role')->dropDownList($usr_roles) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
