<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\IctaCommitteeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Icta Committees';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="icta-committee-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- <p>
        <?= ''//Html::a('Create Icta Committee', ['create'], ['class' => 'btn btn-success']) ?>
    </p>-->

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            'purpose',
            //'date_created',
            //'last_updated',

            ['class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width: 7%'],
                //'visible'=> Yii::$app->user->isGuest ? false : true,
                'template' => '{update} {members}',
                'buttons'=>[
                    'update' => function ($url) {
                        return Html::a('', $url, ['class' => 'glyphicon glyphicon-pencil btn btn-default btn-xs custom_button',
                            'title' =>"Edit ICTA Committee",
                            'onclick'=>"getDataForm('$url', '<h3>Committee Edit</h3>'); return false;"]);
                    },
                    'members' => function ($url, $model) {
                        $url = yii\helpers\Url::to(['icta-committee/add-members','id'=>$model->id]);
                        return Html::a('Add members', $url, ['title' =>"Edit Staff Details",
                            'onclick'=>"getDataForm('$url', '<h3>Record Edit</h3>'); return false;"]);
                    },
                ],
            ],
        ],
    ]); ?>


</div>
