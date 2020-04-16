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
            'user_id',
            'category_id',
            [
                'attribute' => 'status',
                'value' => function($model){
                    if($model->status == 1){
                        return 'Approved';
                    }else if($model->status == 2){
                        return 'Rejected';
                    }
                    return 'Pending';
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
