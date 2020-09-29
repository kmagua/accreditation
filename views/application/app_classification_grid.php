<?php
use yii\helpers\Html;
?>
<div class="company-document-index">
    <span style="font-size: 15pt; font-weight: bold">Scores by Approvers</span>
 <?= yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        //summary' => '',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'application_id',
            'ictaCommittee.name',
            'score',
            'classification',
            'rejection_comment',
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width: 7%'],
                //'visible'=> Yii::$app->user->isGuest ? false : true,
                'template' => '{details}',
                'buttons'=>[                    
                    'details' => function ($url, $model) {
                        $comm = $model->ictaCommittee->name;
                        $url = yii\helpers\Url::to(['application/get-scores', 'id' => $model->application_id, 'l' => $model->icta_committee_id]);
                        return Html::a('', $url, ['class' => 'glyphicon glyphicon-eye-open',
                            'title' =>"View scores by $comm",
                            'onclick'=>"getDataForm('$url', '<h3>Scores by {$comm}</h3>'); return false;"]);
                    },                    
                ],                
            ],
        ],
    ]); ?>
</div>