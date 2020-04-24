<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\professional\models\Application */

?>
<div class="application-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            [
                'attribute' => 'user_id',
                'value' => function($model){
                    return $model->user->first_name . ' ' . $model->user->last_name;
                },                
            ],
            'category.name',
            [
                'label' => 'status',
                'format' => 'raw',
                'value' => function($model){
                    if($model->status == 1){
                        return Html::a('Upload Payment Receipt', [
                            '/professional/application/upload-receipt', 'id'=>$model->id
                        ], 
                        ['onclick' => "getDataForm(this.href, '<h3>Upload Application Payment Receipt</h3>'); return false;"]);
                    }else if($model->status == 2){
                        return 'Rejected';
                    }else if($model->status == 3){
                        return 'Pending Confirmation of Payment';
                    }else if($model->status == 4){
                        return Html::a('Download Certificate', [
                            '/professional/application/download-cert', 'id'=>$model->id
                        ]);
                    }else if($model->status == 5){
                        return 'Payment Rejected';
                    }
                    return 'Pending Approval';
                },                
            ],
            [
                'attribute' => 'declaration',
                'value' => function($model){
                    if($model->declaration == 1){
                        return 'Yes';
                    }
                    return 'No';
                },                
            ],
            //'initial_approval_date',
            'date_created',
            'last_updated',
        ],
    ]) ?>

</div>
