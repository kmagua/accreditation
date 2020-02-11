<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\StaffExperience */

$this->title = 'Create Staff Experience';
$this->params['breadcrumbs'][] = ['label' => 'Staff Experiences', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="staff-experience-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
