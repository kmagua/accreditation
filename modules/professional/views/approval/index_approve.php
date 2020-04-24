<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\modules\professional\models\Application */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="approval-index">
    <p>
        <?php if($model->status == ''){
            $latest_approval = app\modules\professional\models\Approval::find()
                ->where('application_id = ' . $model->id)->orderBy('id desc')->one();
            if(!$latest_approval){
                $link_opts = ['/professional/approval/create-ajax', 'app_id' => $model->id, 'l'=>1];
                echo Html::a('Approve', $link_opts , ['class' => 'btn btn-success',
                'onclick'=>"getDataForm(this.href, '<h4>Approve Professional Accreditation Request</h4>'); return false;"]);
            }else if($latest_approval->level == 1 && $latest_approval->status == 1){
                $link_opts = ['/professional/approval/create-ajax', 'app_id' => $model->id, 'l'=>2];
                echo Html::a('Approve', $link_opts , ['class' => 'btn btn-success',
                'onclick'=>"getDataForm(this.href, '<h4>Approve Professional Accreditation Request</h4>'); "
                    . "return false;"]);
            }
        }
        ?>
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
                    return ($data->status == 1)?"Approved": "Rejected";
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

            ['class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width: 7%'],
                //'visible'=> Yii::$app->user->isGuest ? false : true,
                'template' => '{update}',
                'buttons'=>[
                   
                    'update' => function ($url, $model) {
                
                        return Html::a('', ['/professional/approval/update-ajax', 'id' => $model->id], 
                            ['class' => 'glyphicon glyphicon-pencil btn btn-default btn-xs custom_button',
                            'title' =>"Edit Approval",
                            'onclick'=>"getDataForm(this.href, '<h3>Record Edit</h3>'); return false;"]);
                    },
                ],
            ],
        ],
    ]); ?>


</div>
