<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\StaffExperienceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Staff Experiences';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="staff-experience-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Staff Experience', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'staff_id',
            'organization',
            'role',
            'assignment',
            //'start_date',
            //'end_date',
            //'date_created',
            //'last_updated',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
