<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AccreditationLevel */

$this->title = 'New Accreditation Level';
$this->params['breadcrumbs'][] = ['label' => 'Accreditation Levels', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accreditation-level-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
