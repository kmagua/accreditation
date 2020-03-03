<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\profesional\models\EmploymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Employments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employment-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Employment', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'organisation_name',
            'organisation_email:email',
            'organisation_phone',
            'job_title',
            //'role:ntext',
            //'postal_address',
            //'website',
            //'supervisor_name',
            //'supervisor_designation',
            //'supervisor_email:email',
            //'supervisor_phone',
            //'date_created',
            //'date_modified',
            //'user_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
