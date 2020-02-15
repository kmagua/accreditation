<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProfessionalCertificationSearch */
/* @var $model app\models\ProfessionalCertification */
/* @var $dataProvider yii\data\ActiveDataProvider */

$model = new \app\models\ProfessionalCertification();
$model->staff_id = $searchModel->staff_id;
?>
<div class="professional-certification-index">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'staff_id',
            'qualification_type',
            'other_description',
            [
                'attribute' => 'certificate',
                'content' => function($data){
                    return $data->fileLink(true);
                }
            ],
            //'date_created',
            //'last_updated',

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width: 7%'],
                //'visible'=> Yii::$app->user->isGuest ? false : true,
                'template' => '{update}{delete}',
                'buttons'=>[                    
                    'update' => function ($url, $model) {
                        $url = yii\helpers\Url::to(['professional-certification/update-ajax', 'id'=>$model->id]);
                        return Html::a('', $url, ['class' => 'glyphicon glyphicon-pencil btn btn-default btn-xs custom_button',
                            'title' =>"Certification Details",
                            'onclick'=>"getStaffForm('$url', '<h3>Certification Edit</h3>'); return false;"]);
                    },
                    'delete' => function ($url, $model) {
                        $url = yii\helpers\Url::to(['professional-certification/delete-ajax', 'id'=>$model->id]);
                        $return_link = yii\helpers\Url::to(['professional-certification/data', 'sid'=>$model->staff_id]);
                        return Html::a('', $url, ['class' => 'glyphicon glyphicon-trash', 'title' =>"Delete",
                            'onclick'=>"ajaxDeleteRecord('$url', '$return_link'); return false;"]);
                    },
                ],                
            ],
        ],
    ]); ?>


</div>
