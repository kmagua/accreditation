<?php
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AcademicQualificationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
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