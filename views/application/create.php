<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Application */

$this->title = 'Submit Application';
$this->params['breadcrumbs'][] = ['label' => 'My Applications', 'url' => [
    'company-profile/view','id'=>$model->company_id, '#'=>'application_data_tab']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="application-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
