<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
?>
    <div class="application-index">
    <p>
        <?= Html::a('Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            //'user_id',
            'category.name',
            'user.idno',
            'user.date_of_birth',
            'user.gender',
            'user.phone',
            'user.nationality',
            'user.county',
            'user.postal_address',
            'status',
            'declaration',
            'initial_approval_date',
            //'date_created',
            //'last_updated',
        ],
    ]) ?>

</div>

<div class="education-index">
    <h1>
        Education
    </h1>
    
    <?= GridView::widget([
        'dataProvider' => (new app\modules\professional\models\EducationSearch(['user_id' => $model->user_id]))->
            search(Yii::$app->request->queryParams),
        //'filterModel' => $searchModel,
        'layout' => '{items}',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'level.name',
            'course',
            'institution',
            'completion_date',
            
        ],
    ]); ?>
</div>

<div class="employment-index">
    <h1>
        Employment
    </h1>
    
    <?= GridView::widget([
        'dataProvider' => (new app\modules\professional\models\EmploymentSearch(['user_id' => $model->user_id]))->
            search(Yii::$app->request->queryParams),
        //'filterModel' => $searchModel,
        'layout' => '{items}',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            /*'organisation_name',
            'organisation_email:email',
            'organisation_phone',*/
            [
                'label' => 'Organization',
                'content' => function($data){
                    return $data->organisation_name .  ', '. $data->organisation_email . ', ' .
                       $data->organisation_phone . ', ' . $data->postal_address . ', ' . $data->website;     
                }
            ],
            'job_title',
            'role:ntext',
            /*'supervisor_name',
            'supervisor_designation',
            'supervisor_email:email',
            'supervisor_phone',*/
            [
                'label' => 'Supervisor',
                'content' => function($data){
                    return $data->supervisor_name .  ', '. $data->supervisor_designation . ', ' .
                       $data->supervisor_email . ', ' . $data->supervisor_phone;     
                }
            ],
            
        ],
    ]); ?>
</div>

<div class="employment-index">
    <h1>
        Professional Certifications
    </h1>
    
    <?= GridView::widget([
        'dataProvider' => (new app\modules\professional\models\ProfessionalRegBodiesSearch(['user_id' => $model->user_id]))->
            search(Yii::$app->request->queryParams),
        //'filterModel' => $searchModel,
        'layout' => '{items}',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'membership_no',
            'award_date',
            [
                'attribute' => 'upload',
                'content' => function($data){
                    return $data->fileLink(true);
                }
            ],
        ],
    ]); ?>
</div>