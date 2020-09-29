<?php

use yii\widgets\Pjax;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ApplicationScoreSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Application Scores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="application-score-index">

    <?php Pjax::begin(['enablePushState' => false]); ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'summary' => '',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'scoreItem.category',
            'scoreItem.specific_item',
            'scoreItem.score_item',
            'score',
            'comment',
            //'user_id',
            //'committee_id',
            //'date_created',
            //'last_updated',
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>
