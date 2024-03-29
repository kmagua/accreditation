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
    <?= Html::a('Add Staff', ['new', 'cid'=>$searchModel->company_id], ['class' => 'btn btn-success', 'onclick'=>'getDataForm(this.href); return false;']) ?>
    <h4 style="color:red">Use icon links on 'Actions' column to add academic certificates, certifications and work experience details for a staff.</h4>
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
            //'kra_pin',
            'gender',
            //'dob',
            //'disability_status',
            //'title',
            'staff_type',
            //'status',
            //'date_created',
            //'last_updated',
            [
                'label' => 'Actions',
                'content' => function ($data){
                    return $data->getStaffDetailsLinks();
                }
            ],

            ['class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width: 7%'],
                //'visible'=> Yii::$app->user->isGuest ? false : true,
                'template' => '{view}{update}{delete}',
                'buttons'=>[
                    'view' => function ($url, $model) {
                        return Html::a('', $url, ['class' => 'glyphicon glyphicon-eye-open btn btn-default btn-xs custom_button',
                            'title' =>"Full Staff Details",
                            'onclick'=>"getDataForm('$url', '<h3>Full Staff Details - " . $model->getNames() . "</h3>'); return false;"]);
                    },
                    'update' => function ($url, $model) {
                        $url = yii\helpers\Url::to(['company-staff/update-ajax', 'id'=>$model->id], true);
                        return Html::a('',['company-staff/update-ajax', 'id'=>$model->id] , ['class' => 'glyphicon glyphicon-pencil btn btn-default btn-xs custom_button',
                            'title' =>"Edit Staff Details",
                            'onclick'=>"getDataForm('$url', '<h3>Record Edit</h3>'); return false;"]);
                    },
                    'delete' => function ($url, $model) {
                        $url = yii\helpers\Url::to(['company-staff/delete-ajax', 'id'=>$model->id], true);
                        return Html::a('',['company-staff/delete-ajax', 'id'=>$model->id] , ['class' => 'glyphicon glyphicon-trash btn btn-default btn-xs custom_button',
                            'title' =>"Delete Staff Details",
                            'onclick'=>"getDataForm('$url', '<h3>Record Delete</h3>'); return false;"]);
                    },
                ],
            ],
        ],
    ]); ?>
    
    <?php Pjax::end(); ?>

</div>
<?php //echo FA::icon('home');