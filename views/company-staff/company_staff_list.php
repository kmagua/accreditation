<?php
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CompanyStaffSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-staff-index">
    <?= Html::a('Add Staff', ['new', 'cid'=>$searchModel->company_id], ['class' => 'btn btn-success', 'onclick'=>'getStaffForm(this.href); return false;']) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'company_id',
            'first_name',
            'last_name',
            'national_id',
            'kra_pin',
            'gender',
            //'dob',
            //'disability_status',
            //'title',
            'staff_type',
            //'status',
            //'date_created',
            //'last_updated',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>

<?php

$this->registerJs($js, yii\web\View::POS_END, 'staff_form_js');
