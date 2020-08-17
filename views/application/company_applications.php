<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ApplicationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$model = new app\models\Application();
$model->company_id = $searchModel->company_id;
?>
<div class="application-index">
    <p>
        <?php 
        $missing = app\models\Application::checkCompletedSections($model->company_id);
        if($missing){
            echo "<h4 style='color:red'>The following sections are missing. Please complete before submiting an application.</h4><ul>";
            foreach($missing as $miss){
                echo '<li><span  style="color:red">' , $miss, '</span></li>';
            }
            echo "<br>";
        }
        ?>
        <?= Html::a('Submit a new Application', ['create','cid'=>$model->company_id], ['class' => 'btn btn-success']) ?>
    </p>

    <div id="application_form_div" style="display: none">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'company_id',
            'accreditationType.name',
            'cash_flow',
            'turnover',
            //'financial_status_link',
            
            [
                'attribute' => 'financial_status_link',
                'contentOptions' => ['style' => 'width: 7%'],
                'content' => function($model){
                    return "<a href='{$model->financial_status_link}'>Financial status link</a>";
                },
            ],
            //'user_id',
            [
                'attribute' => 'status',
                'contentOptions' => ['style' => 'width: 7%'],
                'content' => function($model){
                    return $model->getApplicationProgress();
                },
            ],
            //'declaration',
            'date_created',
            //'last_updated',

            ['class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width: 7%'],
                //'visible'=> Yii::$app->user->isGuest ? false : true,
                'template' => '{view}{update}',
                'buttons'=>[
                    'view' => function ($url, $model) {
                        return Html::a('', $url, ['class' => 'glyphicon glyphicon-eye-open btn btn-default btn-xs custom_button',
                            'title' =>"View Application Details"]);
                    },
                    'update' => function ($url) {
                        return Html::a('', $url, ['class' => 'glyphicon glyphicon-pencil btn btn-default btn-xs custom_button',
                            'title' =>"Edit Application Details"]);
                    },
                ],
            ],
        ],
    ]); ?>


</div>
