<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\profesional\models\PersonalInformation */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Personal Informations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="personal-information-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'idno',
            'first_name',
            'last_name',
            'other_names',
            'date_of_birth',
            'gender',
            'phone',
            'nationality',
            'county',
            'postal_address',
            'date_created',
            'date_modified',
        ],
    ]) ?>

</div>
