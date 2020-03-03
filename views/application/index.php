<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\icons\Icon;

\kartik\select2\Select2Asset::register($this);

Icon::map($this, Icon::FAS); 
/* @var $this yii\web\View */
/* @var $searchModel app\models\ApplicationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Applications';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="application-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax'=>true,
        'pjaxSettings'=>[
            'options' => ['id' => 'application_grid_pjax', 'enablePushState'=>false]
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' => 'company',
                'value' => 'company.company_name',
            ],
            [
                'attribute' => 'accreditationType',
                'value' => 'accreditationType.name',
            ],            
            'cash_flow',
            'turnover',
            'financial_status_link',
            //'user_id',
            [
                'attribute' => 'status',
                'contentOptions' => ['style' => 'width: 8%'],
                'content' => function($model){
                    return $model->getApplicationProgress();
                },
            ],
            
            ['class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width: 4%'],
                //'visible'=> Yii::$app->user->isGuest ? false : true,
                'template' => '{view}',
                'buttons'=>[
                    'view' => function ($url, $model) {
                        return Html::a('', $url, ['class' => 'glyphicon glyphicon-eye-open btn btn-default btn-xs custom_button',
                            'title' =>"View Application Details"]);
                    },
                ],
            ],
        ],
    ]); ?>

</div>
<?php
$this->registerJsFile('../js/general_js.js', ['position'=>yii\web\View::POS_END]);
$this->registerJsFile('../js/company_staff.js', ['position'=>yii\web\View::POS_END]);