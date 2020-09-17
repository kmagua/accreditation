<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PreSystemAccreditedCompanies */

$this->title = 'Update Pre System Accredited Companies: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pre System Accredited Companies', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pre-system-accredited-companies-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
