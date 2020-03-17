<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\modules\professional\models\Application */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="approval-index">
    <p><?php if($model->status == ''){ ?>
        <?= Html::a('Approve', ['/professional/approval/create-ajax',
    'app_id' => $model->id], ['class' => 'btn btn-success',
            'onclick'=>"getDataForm(this.href, '<h4>Approve Professional Accreditation Request</h4>'); return false;"]) ?>
    <?php } ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'application_id',
            [
                'attribute' => 'status',
                'content' => function($data){
                    return ($data->level == 1)?"Approved": "Rejected";
                }
            ],
            [
                'attribute' => 'level',
                'content' => function($data){
                    return ($data->level == 1)?"Secretariat": "Committee";
                }
            ],
            'comment',
            [
                'attribute' => 'user_id',
                'content' => function($data){
                    return $data->user->getName();
                }
            ],
            //'date_created',
            //'last_updated',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
