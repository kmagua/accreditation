<?php
use yii\widgets\DetailView;
use yii\helpers\Html;

/* @var $model app\models\CompanyProfile */
?>

<p>
    <?= Html::a("Documents", ['company-document/data', 'cid'=>$model->id], [
        'class' => 'btn btn-success', 'onclick'=>'getStaffForm(this.href, "<h3> Documents for ' . $model->company_name. '</h3>"); return false;'
        ]); 
    ?>
    
    <?= Html::a("Company Projects(Experience)", ['company-experience/data', 'cid'=>$model->id], [
        'class' => 'btn btn-success', 'onclick'=>'getStaffForm(this.href, "<h3>Projects done by ' . $model->company_name. '</h3>"); return false;'
        ]); 
    ?>

</p>

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
        //'user_id',
        //'date_created',
        //'last_updated',
    ],
]) ?>