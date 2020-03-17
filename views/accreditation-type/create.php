<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AccreditationType */

$this->title = 'Create Accreditation Type';
$this->params['breadcrumbs'][] = ['label' => 'Accreditation Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accreditation-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
