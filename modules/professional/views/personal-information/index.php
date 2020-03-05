<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\professional\models\PersonalInformationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Personal Informations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personal-information-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Personal Information', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'idno',
            'first_name',
            'last_name',
            'other_names',
            //'date_of_birth',
            //'gender',
            //'phone',
            //'nationality',
            //'county',
            //'postal_address',
            //'date_created',
            //'date_modified',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
