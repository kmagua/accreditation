<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AccreditationType */

$this->title = 'Update Accreditation Type: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Accreditation Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="accreditation-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
