<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScoreItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Score Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="score-item-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Score Item', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'category',
            'specific_item',
            [
                'attribute' => 'score_item',
                'format' => 'html',
            ],
            'maximum_score',
            //'date_created',
            //'last_updated',

            ['class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width: 7%'],
                //'visible'=> Yii::$app->user->isGuest ? false : true,
                'template' => '{view} {update}',
            ],
        ],
    ]); ?>


</div>
