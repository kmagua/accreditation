<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ApplicationCompanyExperienceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="application-company-experience-index">
    <span style="font-size: x-large; font-weight: bold">Application Company Projects</span>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'summary' => '',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'experience.organization_type',
            'experience.organization_name',
            'experience.project_name',            
            [
                'label' => 'Dates',
                'content' => function($data){
                    return $data->experience->start_date . ' - ' . $data->experience->end_date;
                }
            ],
            'experience.status',
            'experience.project_cost',            
            [
                'attribute' => 'attachment',
                'content' => function($data){
                    return $data->experience->fileLink(true);
                }
            ],
        ],
    ]); ?>


</div>
