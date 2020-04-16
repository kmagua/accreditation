<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ApplicationStaffSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="application-staff-index">
    <span style="font-size: x-large; font-weight: bold">Application Staff</span>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'summary' => '',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'staff.kra_pin',
            [
                'label' => 'Name',
                'content' => function($data){
                    return $data->staff->getNames();
                }
            ],
            
            'role',
            //'date_created',
            //'last_updated',

            [
                'label' => '',
                'headerOptions' => ['style' => 'width:14%'],
                'content' => function($data){
                    return $data->staff->getStaffDetailsLinks(0);
                }
            ],
        ],
    ]); ?>


</div>
