<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ScoreItem */

$this->title = 'Create Score Item';
$this->params['breadcrumbs'][] = ['label' => 'Score Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="score-item-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
