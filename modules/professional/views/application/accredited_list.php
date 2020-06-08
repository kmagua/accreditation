<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\professional\models\ApplicationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Accredited Professionals';
$this->params['breadcrumbs'][] = ['label' => 'Reports', 'url' => ['/site/reports']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="application-index">

    <h3><?= Html::encode($this->title) ?></h3>
    
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php Pjax::begin() ?>
    <?php
    $columns = [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'user.first_name',
            'user.last_name',
            'user.idno',
            'user.gender',
            'category.name',
            //'status',
            [
                'label' => 'Expires On',
                'content' => function($model){
                    return $model->getExpiryDate();
                }
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'content' => function($data){
                    return ($data->status == 4)? 'Complete' : 'Pending Renewal';
                },
                'filter' => [
                    4 => 'Complete',
                    6 => 'Pending Renewal',
                ]
            ],
            //'initial_approval_date',
            //'date_created',
            //'last_updated',

            ['class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width: 7%'],
                //'visible'=> Yii::$app->user->isGuest ? false : true,
                'template' => '{view}',                
            ],
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
        'filename'=>'Accredited Professionals '. date('d-m-Y Hi'),
    ]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
    ]); ?>
    <?php Pjax::end() ?>
</div>
<?php
$this->registerJsFile(Yii::getAlias('@web'). '/js/general_js.js', ['position'=>yii\web\View::POS_END]);
