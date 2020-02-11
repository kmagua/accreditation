<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ProfessionalCertification */

$this->title = 'Create Professional Certification';
$this->params['breadcrumbs'][] = ['label' => 'Professional Certifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="professional-certification-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
