<?php
use yii\grid\GridView;
use yii\helpers\Html;
?>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'staff_id',
            'level',
            'course_name',
            [
                'attribute' => 'certificate',
                'content' => function($data){
                    return $data->fileLink(true);
                }
            ],
            //'date_created',
            //'last_updated',            
        ],
    ]); ?>