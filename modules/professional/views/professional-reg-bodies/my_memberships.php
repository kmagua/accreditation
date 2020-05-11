<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\professional\models\ProfessionalRegBodies */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="education-index">
    <p>
        <?= Html::a('Add Professional Certification Record', ['create-ajax', 'pid'=>$searchModel->user_id], ['class' => 'btn btn-success',
            'onclick'=>'getDataForm(this.href, "<h3>Adding Professional Certification</h3>"); return false;']) ?>
    </p>

    <?php Pjax::begin() ?>

     <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            'membership_no',
            [
                'attribute' => 'upload',
                'content' => function($data){
                    return $data->fileLink(true);
                },
            ],
            'award_date',
            //'date_modified',
            //'user_id',

            ['class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width: 7%'],
                //'visible'=> Yii::$app->user->isGuest ? false : true,
                'template' => '{update}',
                'buttons'=>[
                    'update' => function ($url, $model) {
                        $url = \yii\helpers\Url::to(['professional-reg-bodies/update-ajax', 'id' =>$model->id]);
                        return Html::a('', $url, ['class' => 'glyphicon glyphicon-pencil btn btn-default btn-xs custom_button',
                            'title' =>"Edit Certification Details",
                            'onclick'=>"getDataForm('$url', '<h3>Record Edit</h3>'); return false;"]);
                    },
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end() ?>
</div>
