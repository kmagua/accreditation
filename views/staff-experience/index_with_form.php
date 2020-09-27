<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\StaffExperienceSearch */
/* @var $model app\models\StaffExperience */
/* @var $dataProvider yii\data\ActiveDataProvider */

if(!isset($model)){
    $model = new \app\models\StaffExperience();
    $model->staff_id = $searchModel->staff_id;
    $model->setScenario('create');
}
?>
<div class="staff-experience-index">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

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
            //'end_date',
            //'date_created',
            //'last_updated',

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width: 7%'],
                //'visible'=> Yii::$app->user->isGuest ? false : true,
                'template' => '{update}{delete}',
                'buttons'=>[                    
                    'update' => function ($url, $model) {
                        $url = yii\helpers\Url::to(['staff-experience/update-ajax', 'id'=>$model->id]);
                        return Html::a('', $url, ['class' => 'glyphicon glyphicon-pencil btn btn-default btn-xs custom_button',
                            'title' =>"Edit Work Experience",
                            'onclick'=>"getDataForm('$url', '<h3>Work Experience Edit</h3>'); return false;"]);
                    },
                    'delete' => function ($url, $model) {
                        $url = yii\helpers\Url::to(['staff-experience/delete-ajax', 'id'=>$model->id]);
                        $return_link = yii\helpers\Url::to(['staff-experience/data', 'sid'=>$model->staff_id]);
                        return Html::a('', $url, ['class' => 'glyphicon glyphicon-trash', 'title' =>"Delete",
                            'onclick'=>"ajaxDeleteRecord('$url', '$return_link'); return false;"]);
                    },
                ],                
            ],
        ],
    ]); ?>


</div>
