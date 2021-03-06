<?php
use yii\widgets\DetailView;
use yii\helpers\Html;

/* @var $model app\models\CompanyProfile */
?>
<?= Html::a('Edit', ['company-profile/update', 'id'=>$model->id], ['class'=>'btn btn-primary']) ?>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        //'id',
        'business_reg_no',
        'company_name',
        'registration_date',
        'county',
        'town',
        'building',
        'floor',
        'telephone_number',
        'company_email:email',
        //'type_of_business',
        'postal_address',
        'company_categorization',
        'turnover',
        'cashflow',
        //'user_id',
        //'date_created',
        //'last_updated',
    ],
]) ?>