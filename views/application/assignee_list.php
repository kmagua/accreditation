<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use kartik\icons\Icon;

\kartik\select2\Select2Asset::register($this);

Icon::map($this, Icon::FAS); 
/* @var $this yii\web\View */
/* @var $searchModel app\models\ApplicationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'My Pending Applications';
$this->params['breadcrumbs'][] = ['label' => 'Reports', 'url' => ['site/reports']];
$this->params['breadcrumbs'][] = $this->title;

$columns = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'attribute' => 'previous_category',
        'label' => 'Name',
    ],
    [
        'attribute' => 'id',
        'label' => '# of applications',
    ],      
];
?>
<div class="application-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
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
        //'filterModel' => $searchModel,
        'pjax'=>true,
        'pjaxSettings'=>[
            'options' => ['id' => 'application_grid_pjax', 'enablePushState'=>false]
        ],
        'columns' => $columns,
    ]); ?>

</div>
<?php
$this->registerJsFile('../js/general_js.js', ['position'=>yii\web\View::POS_END]);
$this->registerJsFile('../js/company_staff.js', ['position'=>yii\web\View::POS_END]);