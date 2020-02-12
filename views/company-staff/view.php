<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyStaff */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Company Staff', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="company-staff-view">

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
            //'id',
            'company.company_name',
            'first_name',
            'last_name',
            'national_id',
            'kra_pin',
            'gender',
            'dob',
            'disability_status',
            'title',
            'staff_type',
            [
                'attribute' => 'status',
                'value' => function($model){
                    return ($model->status == 1)?'Active':'Inactive';
                }
            ],
            //'date_created',
            //'last_updated',
        ],
    ]) ?>

</div>
