<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\professional\models\Reference */

$this->title = 'Update Reference: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'References', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="reference-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
