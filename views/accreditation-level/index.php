<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AccreditationLevelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Accreditation Levels';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accreditation-level-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('New Accreditation Level', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            'accreditation_fee',
            'renewal_fee',
            'date_created',
            //'last_updated',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
