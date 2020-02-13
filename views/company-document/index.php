<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CompanyDocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Company Documents';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-document-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Company Document', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'company_id',
            'document_type_id',
             'upload_file',
            'date_created',
            'last_updated',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
