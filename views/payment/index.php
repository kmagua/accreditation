<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\daterange\DateRangePicker;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Payments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php Pjax::begin(['enablePushState'=>false]) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'showFooter' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'application.company.company_name',
            [
                'attribute' =>'billable_amount',
                'footer' => \app\models\Payment::getTotal($dataProvider->models, 'billable_amount'), 
            ],
            //'mpesa_code',
            [
                'attribute' => 'receipt',
                'content' => function($data){
                    return $data->fileLink(true);
                }
            ],
            //'level',
            [
                'attribute' => 'status',
                'content' => function($data){
                    switch($data->status){
                        case 'new':
                            return 'Pending';
                        case 'paid':
                            return 'Pending Confirmation';
                        case 'confirmed':
                            return 'Payment Confirmed';
                        case 'rejected':
                            return 'Payment Rejected';
                    }
                },
                'filter' => ['new' => 'Pending', 'paid'=>'Pending Confirmation', 'confirmed'=>'Payment Confirmed', 'rejected'=>'Payment Rejected'],
            ],            
            //'status',
            [
                'filter' => '<div class="drp-container input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>'.
                    DateRangePicker::widget([
                        'model' => $searchModel,
                        'attribute'  => 'date_range',
                        //'value' => function($data){
                            //return $data->date_created;
                        //},
                        'pluginOptions'=>[ 
                            'locale'=>[
                                'separator'=>' to ',
                                'format' => 'YYYY-MM-DD',
                            ],
                            //'maxSpan' => ['days' => 150],
                            //'opens'=>'right'
                        ],
                        'includeDaysFilter' => true,
                    ]) . '</div>',                        
            ],
        ],
    ]); ?>
    <?php Pjax::end() ?>

</div>
