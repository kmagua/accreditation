<?php

use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\modules\professional\models\Application */
$searchModel = new app\modules\professional\models\ApplicationSearch();
$searchModel->parent_id = $model->id;
$dataProvider = $searchModel->searchRenewals([]);
?>
<div class="application-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            [
                'attribute' => 'user_id',
                'value' => function($model){
                    return $model->user->first_name . ' ' . $model->user->last_name;
                },                
            ],
            'category.name',
            [
                'label' => 'status',
                'format' => 'raw',
                'value' => function($model){
                    return $model->getUserStatus();
                },                
            ],
            [
                'attribute' => 'declaration',
                'value' => function($model){
                    if($model->declaration == 1){
                        return 'Yes';
                    }
                    return 'No';
                },                
            ],
            //'initial_approval_date',
            'date_created',
            'last_updated',
        ],
    ]) ?>

</div>
<div class="application-index">
    <h3><u>RENEWALS</u></h3>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
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
                'format' => 'raw',
                'content' => function($data){
                    return $data->getStatus();
                },
                'filter' => [
                    1 => 'Pending Payment',
                    2 => 'Rejected',
                    3 => 'Pending Payment Confirmation',
                    4 => 'Complete',
                    5 => 'Payment Rejected',
                    6 => 'Pending Renewal',
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