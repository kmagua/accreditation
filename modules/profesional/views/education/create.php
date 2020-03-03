<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\profesional\models\Education */

$this->title = 'Create Education';
$this->params['breadcrumbs'][] = ['label' => 'Educations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="education-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
