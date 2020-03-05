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
            'status',
            'declaration',
            'initial_approval_date',
            'date_created',
            'last_updated',
        ],
    ]) ?>

</div>
