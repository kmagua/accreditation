<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\professional\models\EmploymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="education-index">
    <p>
        <?= Html::a('Add Employment Record', ['create-ajax', 'pid'=>$searchModel->user_id], ['class' => 'btn btn-success',
            'onclick'=>'getDataForm(this.href, "<h3>Adding Employment</h3>"); return false;']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
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

            ['class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width: 7%'],
                //'visible'=> Yii::$app->user->isGuest ? false : true,
                'template' => '{update}',
                'buttons'=>[
                    'update' => function ($url, $model) {
                        $url = \yii\helpers\Url::to(['employment/update-ajax', 'id' =>$model->id]);
                        return Html::a('', $url, ['class' => 'glyphicon glyphicon-pencil btn btn-default btn-xs custom_button',
                            'title' =>"Edit Employment Details",
                            'onclick'=>"getDataForm('$url', '<h3>Record Edit</h3>'); return false;"]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
