<div class="company-document-index">
    <span style="font-size: 15pt; font-weight: bold">Assigned Reviewers</span>
 <?= yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'application_id',
            'committeeMember.committee.name',
            [
                'label' => 'Name',
                'content' => function($data){
                    return $data->committeeMember->user->first_name .' ' . $data->committeeMember->user->last_name;
                }
            ],
            //'date_created',
            //'last_updated',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>