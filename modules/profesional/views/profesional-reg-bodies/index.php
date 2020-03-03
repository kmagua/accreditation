<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\profesional\models\ProfesionalRegBodiesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Profesional Reg Bodies';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="profesional-reg-bodies-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Profesional Reg Bodies', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'membership_no',
            'upload',
            'date_created',
            //'date_modified',
            //'user_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
