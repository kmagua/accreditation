<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ProfessionalCertification */

$this->title = 'Update Professional Certification: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Professional Certifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="professional-certification-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
