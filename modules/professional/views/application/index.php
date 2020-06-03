<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\professional\models\ApplicationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'ICT Professional Certification Applications';
if(isset($is_renewal)){
    $this->title = 'ICT Professional Certification Renewals';
    $this->params['breadcrumbs'][] = ['label' => 'Applications', 'url' => ['/professional/appliction/index']];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="application-index">

    <h3><?= Html::encode($this->title) ?></h3>
    
    <?php if(isset($is_renewal)){
        echo Html::a('Back to Original Applications', ['/professional/application/index'], 
            ['class' => 'btn btn-primary', 'title' =>"Original Applications"]);
    }else{
        echo Html::a('Professional Renewals', ['/professional/application/renewals'], 
            ['class' => 'btn btn-primary', 'title' =>"Renewals"]);
    }?>
    
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php Pjax::begin() ?>
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
    <?php Pjax::end() ?>
</div>
<?php
$this->registerJsFile(Yii::getAlias('@web'). '/js/general_js.js', ['position'=>yii\web\View::POS_END]);
