<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\professional\models\ProfessionalRegBodies */

$this->title = 'Update Professional Reg Bodies: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Professional Reg Bodies', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="professional-reg-bodies-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
