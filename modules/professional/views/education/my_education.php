<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\professional\models\EducationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="education-index">
    <p>
        <?= Html::a('Add Education Record', ['create-ajax', 'pid'=>$searchModel->user_id], ['class' => 'btn btn-success',
            'onclick'=>'getDataForm(this.href, "<h3>Adding Education</h3>"); return false;']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'level.name',
            'course',
            'institution',
            'completion_date',
            //'date_created',
            //'date_modified',
            //'user_id',

            ['class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width: 7%'],
                //'visible'=> Yii::$app->user->isGuest ? false : true,
                'template' => '{update}',
                'buttons'=>[
                    'update' => function ($url, $model) {
                        $url = \yii\helpers\Url::to(['education/update-ajax', 'id' =>$model->id]);
                        return Html::a('', $url, ['class' => 'glyphicon glyphicon-pencil btn btn-default btn-xs custom_button',
                            'title' =>"Edit Education Details",
                            'onclick'=>"getDataForm('$url', '<h3>Record Edit</h3>'); return false;"]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
