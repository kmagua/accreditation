<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyProfile */

$this->title = 'Updating Company Profile: ';
$this->params['breadcrumbs'][] = ['label' => 'Company Profiles',
    'url' => \Yii::$app->user->identity->isInternal()?['index']:['my-companies']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="company-profile-update">
<div class="bordered">
    <h5><?= Html::encode($this->title) ?></h5>

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
