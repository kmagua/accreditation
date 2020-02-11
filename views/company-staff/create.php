<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyStaff */

$this->title = 'Create Company Staff';
$this->params['breadcrumbs'][] = ['label' => 'Company Staff', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-staff-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
