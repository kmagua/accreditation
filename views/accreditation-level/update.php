<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AccreditationLevel */

$this->title = 'Editing Accreditation Level: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Accreditation Levels', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="accreditation-level-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
