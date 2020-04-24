<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <p>
        <?= Html::a('New User', ['new'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'email:email',
            'first_name',
            'last_name',
            //'password',
            'role',
            //'date_created',
            //'last_updated',

            ['class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width: 4%'],
                //'visible'=> Yii::$app->user->isGuest ? false : true,
                'template' => '{view} {update} {change_role}',
                'buttons'=>[
                    'view' => function ($url, $model) {
                        return Html::a('', $url, ['class' => 'glyphicon glyphicon-eye-open btn btn-default btn-xs custom_button',
                            'title' =>"Full Staff Details"]);
                    },
                    'change_role' => function ($url, $model) {
                        return Html::a('', ['user/change-role', 'id' => $model->id], ['class' => 'glyphicon glyphicon-lock',
                            'title' =>"Chnage User Role"]);
                    },
                ],
            ],
        ],
    ]); ?>


</div>
