<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\profesional\models\ProfesionalRegBodies */

$this->title = 'Update Profesional Reg Bodies: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Profesional Reg Bodies', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="profesional-reg-bodies-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
