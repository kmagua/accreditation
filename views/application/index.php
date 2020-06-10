<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\icons\Icon;

\kartik\select2\Select2Asset::register($this);

Icon::map($this, Icon::FAS); 
/* @var $this yii\web\View */
/* @var $searchModel app\models\ApplicationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Supplier Applications';
if(isset($is_renewal)){
    $this->title = 'Supplier Application Renewals';
    $this->params['breadcrumbs'][] = ['label' => 'Applications', 'url' => ['/application/index']];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="application-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if(isset($is_renewal)){
        echo Html::a('<< Original Applications', ['/application/index'], 
            ['class' => 'btn btn-primary', 'title' =>"Original Applications"]);
    }else{
        echo Html::a('>> Supplier Renewals', ['/application/renewals'], 
            ['class' => 'btn btn-primary', 'title' =>"Renewals"]);
    }?>

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
            ],*/
            //'user_id',
            [
                'attribute' => 'status',
                'contentOptions' => ['style' => 'width: 12%'],
                'content' => function($model){
                    return $model->getApplicationProgress();
                },
                'filter' => [
                    'draft' => 'New/Pending Secretariat Assignment',
                    'at-secretariat' => 'At Secretariat',
                    'assign-approval-committee' => 'Pending Committee Assignment',
                    'sec-rejected' => 'Rejected at Secretariat',
                    'at-committee' => 'At Committee',
                    'approved' => 'approved',
                    'com-rejected' => 'Rejected at Committee',
                    'certificate-paid' => 'Pending Payment Confirmation',
                    'approval-payment-rejected' => 'Payment Rejected',
                    'completed' => 'Completed',
                    'renewal' => 'Pending Renewal',
                    'renewed' => 'Pending Renewal Approval'
                ]
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