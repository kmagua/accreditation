<?php

use yii\grid\GridView;
use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-6">
                <h2>My Companies</h2>
                <?php if(!Yii::$app->user->isGuest): ?>
                <?php
                
                $searchModel = new app\models\CompanyProfileSearch();
                $searchModel->user_id = Yii::$app->user->identity->user_id;
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                ?>
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
                            'template' => '{view}',
                            'buttons'=>[
                                'view' => function ($url, $model) {
                                    return Html::a('', ['company-profile/view', 'id'=>$model->id], ['class' => 'glyphicon glyphicon-eye-open btn btn-default btn-xs custom_button',
                                        'title' =>"View"]);
                                },
                            ],
                        ],
                    ],
                ]); ?>
                <?= Html::a('Add new Company', ['company-profile/create'], ['class' => 'btn btn-success']) ?>
                <?php else: ?>
                <p>Login to see you compmnies or register a new one!</p>
                <?php endif; ?>
            </div>
            <div class="col-lg-6">
                <h2>User Information</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                
            </div>
            
        </div>

    </div>
</div>
