<div class="company-document-index">
    <span style="font-size: 15pt; font-weight: bold">Payments</span>
<?= \yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'summary' => '',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'application_id',
            'billable_amount',
            //'mpesa_code',
            //'receipt',
            //'level',
            'status',
            [
                'attribute' => 'upload_file',
                'content' => function($data){
                    return $data->getReceipt(false);
                }
            ],            
            //'last_update',
        ],
    ]); ?>
</div>