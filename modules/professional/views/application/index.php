<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\professional\models\ApplicationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'ICT Professional Certification Applications';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="application-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'user.first_name',
            'user.last_name',
            'user.idno',
            'user.gender',
            'category.name',
            //'status',
            [
                'attribute' => 'status',
                'content' => function($data){
                    if($data->status == 1){
                        return 'Approved';
                    }else if($data->status == 2){
                        return 'Rejected';
                    }
                    return 'Pending';
                },
                'filter' => [
                    1 => 'Approved',
                    2 => 'Rejected',
                    10 => 'Pending',
                ]
            ],
            //'initial_approval_date',
            //'date_created',
            //'last_updated',

            ['class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width: 7%'],
                //'visible'=> Yii::$app->user->isGuest ? false : true,
                'template' => '{view}',                
            ],
        ],
    ]); ?>


</div>
