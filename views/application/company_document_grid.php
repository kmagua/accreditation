<div class="company-document-index">
    <span style="font-size: 15pt; font-weight: bold">Company Documents</span>
    
<?= \yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'summary' => '',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'company_id',
            'companyTypeDoc.documentType.name',
            [
                'attribute' => 'upload_file',
                'content' => function($data){
                    return $data->fileLink(true);
                }
            ],
            //'date_created',
            //'last_updated',
        ],
    ]); ?>
</div>