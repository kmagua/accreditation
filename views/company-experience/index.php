<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CompanyExperienceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$model = new \app\models\CompanyExperience();
$model->company_id = $searchModel->company_id;
?>
<div class="company-experience-index">

    <?= Html::a("Add Company Project(Experience)", ['company-experience/data', 'cid'=>$model->company_id], [
        'class' => 'btn btn-success', 'onclick'=>'getDataForm(this.href, "<h3>Projects done by ' . $model->company->company_name. '</h3>"); return false;'
        ]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'company_id',
            'organization_type',
            'project_name',
            [
                'label' => 'Dates',
                'content' => function($data){
                    return $data->start_date . ' - ' . $data->end_date;
                }
            ],
            'status',
            'project_cost',            
            [
                'attribute' => 'attachment',
                'content' => function($data){
                    return $data->fileLink(true);
                }
            ],
            //'date_created',
            //'last_updated',

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width: 7%'],
                //'visible'=> Yii::$app->user->isGuest ? false : true,
                'template' => '{update}{delete}',
                'buttons'=>[                    
                    'update' => function ($url, $model) {
                        $url = yii\helpers\Url::to(['company-experience/update-ajax', 'id'=>$model->id]);
                        return Html::a('', $url, ['class' => 'glyphicon glyphicon-pencil btn btn-default btn-xs custom_button',
                            'title' =>"Edit Work Experience",
                            'onclick'=>"getDataForm('$url', '<h3>Work Experience Edit</h3>'); return false;"]);
                    },
                    'delete' => function ($url, $model) {
                        $url = yii\helpers\Url::to(['company-experience/delete-ajax', 'id'=>$model->id]);
                        $return_link = yii\helpers\Url::to(['company-experience/data', 'cid'=>$model->company_id]);
                        return Html::a('', $url, ['class' => 'glyphicon glyphicon-trash', 'title' =>"Delete",
                            'onclick'=>"ajaxDeleteRecord('$url', '$return_link'); return false;"]);
                    },
                ],                
            ],
        ],
    ]); ?>


</div>
