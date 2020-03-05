<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\professional\models\PersonalInformation */

$this->title = 'Personal Information Form';
$this->params['breadcrumbs'][] = ['label' => 'Personal Informations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personal-information-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
