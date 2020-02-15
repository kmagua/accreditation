<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CompanyProfileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Company List';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-profile-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'business_reg_no',
            'company_name',
            'registration_date',
            'county',
            //'town',
            //'building',
            //'floor',
            //'telephone_number',
            //'company_email:email',
            //'company_type_id',
            //'postal_address',
            //'company_categorization',
            //'user_id',
            //'date_created',
            //'last_updated',

            ['class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width: 4%'],
                //'visible'=> Yii::$app->user->isGuest ? false : true,
                'template' => '{view}',
                'buttons'=>[
                    'view' => function ($url, $model) {
                        return Html::a('', $url, ['class' => 'glyphicon glyphicon-eye-open btn btn-default btn-xs custom_button',
                            'title' =>"Full Staff Details"]);
                    },
                ],
            ],
        ],
    ]); ?>


</div>
