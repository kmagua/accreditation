<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\professional\models\ProfessionalRegBodies */

$this->title = 'Create Professional Reg Bodies';
$this->params['breadcrumbs'][] = ['label' => 'Professional Reg Bodies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="professional-reg-bodies-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
