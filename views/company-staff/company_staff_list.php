<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CompanyStaffSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-staff-index">
    <?= Html::a('Add Staff', ['new', 'cid'=>$searchModel->company_id], ['class' => 'btn btn-success', 'onclick'=>'getStaffForm(this.href); return false;']) ?>
    
    <?php Pjax::begin(['id' => 'staff-data-list', 'timeout' => false, 'enablePushState' => false]); ?>
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

            ['class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width: 7%'],
                //'visible'=> Yii::$app->user->isGuest ? false : true,
                'template' => '{view}{update}{academic}',
                'buttons'=>[
                    'view' => function ($url) {
                        return Html::a('', $url, ['class' => 'glyphicon glyphicon-eye-open btn btn-default btn-xs custom_button',
                            'title' =>"Full Staff Details",
                            'onclick'=>"getStaffForm('$url', '<h3>Staff Details</h3>'); return false;"]);
                    },
                    'update' => function ($url) {
                        return Html::a('', $url, ['class' => 'glyphicon glyphicon-pencil btn btn-default btn-xs custom_button',
                            'title' =>"Edit Staff Details",
                            'onclick'=>"getStaffForm('$url', '<h3>Record Edit</h3>'); return false;"]);
                    },
                    'academic' => function ($url, $model) {
                        $url = yii\helpers\Url::to(['academic-qualification/data', 'sid'=>$model->id]);
                        return Html::a('', $url, ['class' => 'fas fa-graduation-cap', 'title' =>"Staff's Academic Qualifications",
                            'onclick'=>"getStaffForm('$url', '<h3>Academic Qualifications for " . $model->getNames() . "</h3>'); return false;"]);
                    },
                ],
            ],
        ],
    ]); ?>
    
    <?php Pjax::end(); ?>

</div>
<?php //echo FA::icon('home');