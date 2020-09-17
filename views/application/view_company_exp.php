<?php
$asSearchModel = \app\models\ApplicationCompanyExperience::find()->select("experience_id")->
    
    where(['application_id' => $app_id])->asArray()->all();

if($asSearchModel){
    $searchCP = new app\models\CompanyExperienceSearch();
    $searchCP->id = array_column($asSearchModel, 'experience_id');
    $cpDataProvider = $searchCP->search([]);

    echo \yii\grid\GridView::widget([
        'dataProvider' => $cpDataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'company_id',
            'organization_type',
            'organization_name',
            'project_name',
            [
                'label' => 'Dates',
                'content' => function($data){
                    return $data->start_date . ' - ' . $data->end_date;
                }
            ],
            'status',
            'project_cost',            
            [
                'attribute' => 'attachment',
                'content' => function($data){
                    return $data->fileLink(true);
                }
            ],
        ]        
    ]);
}