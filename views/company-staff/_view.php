<?php
use yii\widgets\DetailView;
?>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        //'id',
        'company.company_name',
        'first_name',
        'last_name',
        'national_id',
        'kra_pin',
        'gender',
        'dob',
        'disability_status',
        'title',
        'staff_type',
        [
            'attribute' => 'status',
            'value' => function($model){
                return ($model->status == 1)?'Active':'Inactive';
            }
        ],
        //'date_created',
        //'last_updated',
    ],
]) ?>