<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyProfile */

$this->title = 'Update Company Profile: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Company Profiles', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="company-profile-update">
<div class="bordered">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
  </div>

<style>
  .bordered {
    width: 1200px;
    height: 600px;
    padding: 20px;
    border: 1px solid darkorange;
    border-radius: 8px;
  }
</style>