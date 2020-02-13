<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyDocument */

$this->title = 'Create Company Document';
$this->params['breadcrumbs'][] = ['label' => 'Company Documents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-document-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
