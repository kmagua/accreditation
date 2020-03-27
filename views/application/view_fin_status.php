<?= yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => [            
        'cash_flow',
        'turnover',
        'financial_status_link',
    ],
]) ?>