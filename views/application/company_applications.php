<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ApplicationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$model = new app\models\Application();
$model->company_id = $searchModel->company_id;
?>
<div class="application-index">
    <p>
        <?= '' /*Html::a('Submit Application', ['new','cid'=>$model->company_id], ['class' => 'btn btn-success',
            'onclick'=>'getStaffForm(this.href); return false;'])*/ ?>
        <?= Html::a('Submit Application', ['create','cid'=>$model->company_id], ['class' => 'btn btn-success']) ?>
    </p>

    <div id="application_form_div" style="display: none">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'company_id',
            'accediation_category_id',
            'financial_status_amount',
            'financial_status_link',
            //'user_id',
            'status',
            //'declaration',
            //'date_created',
            //'last_updated',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
