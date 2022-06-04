<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\icons\Icon;

\kartik\select2\Select2Asset::register($this);

Icon::map($this, Icon::FAS); 
/* @var $this yii\web\View */
/* @var $searchModel app\models\ApplicationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'My Pending Reviews';

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="application-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
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
            'company.business_reg_no',
            [
                'attribute' => 'accreditationType',
                'value' => 'accreditationType.name',
            ],            
            'cash_flow',
            'turnover',
            //''
            /*[
                'attribute' => 'financial_status_link',
                'label' => 'Audited Accounts',
                'contentOptions' => ['style' => 'width: 4%'],
                'content' => function($model){
                    return "<a href='{$model->financial_status_link}'>open</a>";
                },
            ]
            [
                'attribute' =>'date_created',
                'label'=>'Application Date',
                'filter' => false,
                'content' => function($data){
                    return date('d-m-Y', strtotime($data->date_created));
                }     
            ],,*/
            [
                'attribute' => 'status',
                'contentOptions' => ['style' => 'width: 12%'],
                'content' => function($model){
                    return $model->getApplicationProgress();
                },
            ],
            
            [
                'class' => 'yii\grid\ActionColumn',
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