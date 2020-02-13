<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProfessionalCertificationSearch */
/* @var $model app\models\ProfessionalCertification */
/* @var $dataProvider yii\data\ActiveDataProvider */


?>
<div class="professional-certification-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'staff_id',
            'qualification_type',
            'other_description',
            [
                'attribute' => 'certificate',
                'content' => function($data){
                    return $data->fileLink(true);
                }
            ],
            //'date_created',
            //'last_updated',
        ],
    ]); ?>
</div>
