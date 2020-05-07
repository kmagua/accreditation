<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\professional\models\Reference */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="education-index">
    Please provide the following:
    <ul>
        <li>Letters of reference from employer(s) covering the previous two years confirming professional integrity</li>
        <li>Statements of two referees detailing their knowledge of you (the applicant).</li>
    </ul>
    <p>
        <?= Html::a('Add Reference Record', ['create-ajax', 'pid'=>$searchModel->user_id], ['class' => 'btn btn-success',
            'onclick'=>'getDataForm(this.href, "<h3>Adding References</h3>"); return false;']) ?>
    </p>

    <?php Pjax::begin() ?>

     <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'user_id',            
            'type',            
            //'last_updated',
            [
                'attribute' => 'upload',
                'content' => function($data){
                    return $data->fileLink(true);
                },
            ],
            'date_created',
            'last_updated',
            //'user_id',

            ['class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width: 7%'],
                //'visible'=> Yii::$app->user->isGuest ? false : true,
                'template' => '{update}',
                'buttons'=>[
                    'update' => function ($url, $model) {
                        $url = \yii\helpers\Url::to(['reference/update-ajax', 'id' =>$model->id]);
                        return Html::a('', $url, ['class' => 'glyphicon glyphicon-pencil btn btn-default btn-xs custom_button',
                            'title' =>"Edit Reference Details",
                            'onclick'=>"getDataForm('$url', '<h3>Record Edit</h3>'); return false;"]);
                    },
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end() ?>
</div>
