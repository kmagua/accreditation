<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\profesional\models\ProfesionalRegBodies */

$this->title = 'Create Profesional Reg Bodies';
$this->params['breadcrumbs'][] = ['label' => 'Profesional Reg Bodies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="profesional-reg-bodies-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
