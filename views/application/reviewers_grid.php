<div class="company-document-index">
    <span style="font-size: 15pt; font-weight: bold">Assigned Reviewers</span>
 <?= yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'summary' => '',
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
            [
                'label' => 'Re-assign',
                'content' => function($data){
                    $show = false;
                    if(($data->application->status== 'ApplicationWorkflow/at-secretariat' && $data->committeeMember->committee_id == 1)){
                        $show = true;
                    }else if($data->application->status== 'ApplicationWorkflow/at-committee' && $data->committeeMember->committee_id == 2 ){
                        $show = true;
                    }
                    if($show){
                        return yii\helpers\Html::a('Re-assign', ['application/committee-members',
                            'id'=>$data->application_id, 'l'=>$data->committeeMember->committee_id, 'cn'=>'yes'], [
                                'data-pjax'=>'0', 'onclick' => "getDataForm(this.href, '<h3>Re Assign to Different Members</h3>'); return false;",
                                'title' => 'Re assign to different persons'
                        ]);
                    }
                    return 'N/A';// . $data->application->status . ' ' . $data->committeeMember->committee_id;
                },
                'visible' => in_array(Yii::$app->user->identity->username, ['charles.waithiru@ict.go.ke', 'charles.waithiru@icta.go.ke', 'james.wafula@ict.go.ke', 'james.wafula@icta.go.ke', 'kenmagua@gmail.com']),
            ],
            //'date_created',
            //'last_updated',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>