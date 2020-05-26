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
                    return $model->getUserStatus();
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
