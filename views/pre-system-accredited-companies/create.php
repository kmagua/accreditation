<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PreSystemAccreditedCompanies */

$this->title = 'Create Pre System Accredited Companies';
$this->params['breadcrumbs'][] = ['label' => 'Pre System Accredited Companies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pre-system-accredited-companies-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
