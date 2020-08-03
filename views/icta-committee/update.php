<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\IctaCommittee */

$this->title = 'Edit Approval Level: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Icta Committees', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="icta-committee-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
