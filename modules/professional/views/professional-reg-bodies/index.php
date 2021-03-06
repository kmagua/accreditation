<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\professional\models\ProfessionalRegBodiesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Professional Reg Bodies';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="professional-reg-bodies-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Professional Reg Bodies', ['create'], ['class' => 'btn btn-success']) ?>
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
            [
                'attribute' => 'upload',
                'content' => function($data){
                    return $data->fileLink(true);
                },
            ],
            //'date_created',
            //'date_modified',
            //'user_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
