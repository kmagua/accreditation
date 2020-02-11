<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyProfile */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Company Profiles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="company-profile-view">

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
            'business_reg_no',
            'company_name',
            'registration_date',
            'county',
            'town',
            'building',
            'floor',
            'telephone_number',
            'company_email:email',
            'type_of_business',
            'postal_address',
            'company_categorization',
            'user_id',
            'date_created',
            'last_updated',
        ],
    ]) ?>

</div>
