<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = "Renewal";

$this->params['breadcrumbs'][] = ['label' => 'My Profile', 'url' => ['/professional/personal-information/view', 'id' =>$piid]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="renewal-form" style="border:1px solid red; padding: 10px">
    <p><strong>CPD is defined as the undertaking of development activities that lead to the systematic maintenance,
    improvement and broadening of knowledge and skills, and the development of personal qualities
    necessary for the execution of professional and technical duties throughout a person`s ICT
    professional career.</strong></p>
    <p><strong>CPD Requirements</strong></p>
    <ol type="a">
        <li>
            Certified Professionals (CP) must complete 90 CPD hours over a period of three years.
        </li>
        <li>Members shall demonstrate commitment to professional development via written evidence of
            CPD activities.</li>
    </ol>

    <p><strong>Sources of CPD</strong></p>
    <ol type="a">
        <li>Attend conferences, seminars, training courses, presentations.@30 CPDs</li>
        <li>Present papers at conferences and seminars, write articles for journals (Contributions to
            knowledge)@30 CPDs</li>
    </ol>
    <div class="form-group">
        <?= Html::a('Add CPD Record for Renewal', ['/professional/renewal-cpd/create-ajax', 'appid' => $renewal_id], 
                ['class' => 'btn btn-success', 'title' =>"Add CPD Record for Renewal",
                'onclick'=>"getDataForm(this.href, '<h3>Add CPD Record for Renewal</h3>'); return false;"]); ?>
        
        <?php if($renewal->status == 12){ ?>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <?= Html::a('Submit Renewal for Consideration (After Completing upload of CPDs)', 
                ['/professional/application/update-renewal', 'id' => $renewal_id], 
                [
                    'class' => 'btn btn-danger', 'title' =>"Submit renewal for Consideration",
                    'onclick'=>"getDataForm(this.href, '<h3>Submit Renewal</h3>'); return false;"
                ]
        ); ?>
        <?php } ?>
        
    </div>
    <?= GridView::widget([
        'dataProvider' => \app\modules\professional\models\Application::getRenewals($mother_app_id),
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'renewal_id',
            'type',
            'description',
            'start_date',
            //'end_date',
            [
                'attribute' => 'upload',
                'content' => function($data){
                    return $data->fileLink(true);
                },
            ],
            //'date_created',
            //'last_modified',

            ['class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width: 7%'],
                //'visible'=> Yii::$app->user->isGuest ? false : true,
                'template' => '{update}',
                'buttons'=>[
                    'update' => function ($url, $model) {
                        $url = \yii\helpers\Url::to(['renewal-cpd/update-ajax', 'id' =>$model->id]);
                        return Html::a('', $url, ['class' => 'glyphicon glyphicon-pencil btn btn-default btn-xs custom_button',
                            'title' =>"Edit Renewal CPD Details",
                            'onclick'=>"getDataForm('$url', '<h3>CPD Record Edit</h3>'); return false;"]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
<?php
$this->registerJsFile(Yii::getAlias('@web'). '/js/general_js.js', ['position'=>yii\web\View::POS_END]);