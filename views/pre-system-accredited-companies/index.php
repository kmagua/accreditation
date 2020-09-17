<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PreSystemAccreditedCompaniesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pre System Accredited Companies';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pre-system-accredited-companies-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'cert_reference',
            'company_name',
            'date_of_accreditation',
            'valid_till',
            'service_category',
            //'to_go',
            'icta_grade',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
