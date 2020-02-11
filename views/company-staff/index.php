<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CompanyStaffSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Company Staff';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-staff-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Company Staff', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'company_id',
            'first_name',
            'last_name',
            'national_id',
            //'kra_pin',
            //'gender',
            //'dob',
            //'disability_status',
            //'title',
            //'staff_type',
            //'status',
            //'date_created',
            //'last_updated',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
