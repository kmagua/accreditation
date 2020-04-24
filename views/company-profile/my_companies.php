<?php
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'My Companies';
?>
<div class="company-profile-index">
    <h4>Use the new Button below to register a new company or click the 'view' icon for further information on an already registered company.</h4>
    <?= Html::a('Add new Company', ['company-profile/create'], ['class' => 'btn btn-success']) ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    //'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'business_reg_no',
        'company_name',

        ['class' => 'yii\grid\ActionColumn',
            'contentOptions' => ['style' => 'width: 7%'],
            //'visible'=> Yii::$app->user->isGuest ? false : true,
            'template' => '{view} {update}',
            'buttons'=>[
                'view' => function ($url, $model) {
                    return Html::a('', ['company-profile/view', 'id'=>$model->id], ['class' => 'glyphicon glyphicon-eye-open btn btn-default btn-xs custom_button',
                        'title' =>"View"]);
                },
            ],
        ],
    ],
]); ?>
</div>