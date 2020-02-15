<?php
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\StaffExperienceSearch */
/* @var $model app\models\StaffExperience */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="staff-experience-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'staff_id',
            'organization',
            'role',
            'assignment',
            [
                'label' => 'Dates',
                'content' => function($data){
                    return $data->start_date . ' - ' . $data->end_date;
                }
            ],
        ],
    ]); ?>


</div>
