<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CompanyProfileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Company Profiles';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-profile-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Company Profile', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'business_reg_no',
            'company_name',
            'registration_date',
            'county',
            //'town',
            //'building',
            //'floor',
            //'telephone_number',
            //'company_email:email',
            //'type_of_business',
            //'postal_address',
            //'company_categorization',
            //'user_id',
            //'date_created',
            //'last_updated',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
