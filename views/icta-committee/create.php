<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\IctaCommittee */

$this->title = 'Create Icta Committee';
$this->params['breadcrumbs'][] = ['label' => 'Icta Committees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="icta-committee-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
