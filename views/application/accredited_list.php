<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\export\ExportMenu;

\kartik\select2\Select2Asset::register($this);


/* @var $this yii\web\View */
/* @var $searchModel app\models\ApplicationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Accredited Suppliers';
$this->params['breadcrumbs'][] = ['label' => 'Reports', 'url' => ['/site/reports']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="application-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php 
    $columns = [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'company',
            'value' => 'company.company_name',
        ],
        'company.business_reg_no',
        'company.company_email',
        [
            'attribute' => 'accreditationType',
            'value' => 'accreditationType.name',
        ],
        [
            'label' => 'Expires On',
            'content' => function($model){
                return $model->getExpiryDate();
            }
        ],
        [
            'attribute' => 'status',
            'contentOptions' => ['style' => 'width: 12%'],
            'content' => function($model){
                return ($model->status == 'ApplicationWorkflow/completed')? "Completed":"Pending Renewal";
            },
            'filter' => [                    
                'ApplicationWorkflow/completed' => 'Completed',
                'ApplicationWorkflow/renewal' => 'Pending Renewal'
            ]
        ]        
    ];
    ?>
    <?= ExportMenu::widget([
        'dataProvider' => $dataProvider,
        'columns' => $columns,
        'exportConfig' => [
            ExportMenu::FORMAT_TEXT => false,
            ExportMenu::FORMAT_PDF => false,
            ExportMenu::FORMAT_HTML => false,
            ExportMenu::FORMAT_CSV => false,
        ],
        'showConfirmAlert'=>false,
        'filename'=>'Accredited Suppliers '. date('d-m-Y Hi'),
    ]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax'=>true,
        'pjaxSettings'=>[
            'options' => ['id' => 'applications_grid_pjax', 'enablePushState'=>false]
        ],
        'columns' => $columns,
    ]); ?>

</div>
<?php
$this->registerJsFile('../js/general_js.js', ['position'=>yii\web\View::POS_END]);
$this->registerJsFile('../js/company_staff.js', ['position'=>yii\web\View::POS_END]);