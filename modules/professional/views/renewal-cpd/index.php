<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\professional\models\RenewalCpdSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Renewal Cpds';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="renewal-cpd-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Renewal Cpd', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'renewal_id',
            'type',
            'description',
            'start_date',
            //'end_date',
            //'upload',
            //'date_created',
            //'last_modified',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
