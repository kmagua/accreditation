<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\icons\Icon;

$this->registerCss(
"table.detail-view th {
    width: 40%;
}");

\kartik\select2\Select2Asset::register($this);

Icon::map($this, Icon::FAS); 

/* @var $this yii\web\View */
/* @var $model app\models\Application */

$this->title =  "Application for " . $model->accreditationType->name . " Accreditation.";
if(Yii::$app->user->identity->isInternal()){
    $this->params['breadcrumbs'][] = ['label' => 'Applications', 'url' => ['application/index']];
}else{
    $this->params['breadcrumbs'][] = ['label' => 'My Applications', 'url' => [
    'company-profile/view','id'=>$model->company_id, '#'=>'application_data_tab']];
}
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="application-view">

    <h1><?= $this->title ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'company.company_name',
            'company.business_reg_no',
            'company.company_name',
            'company.registration_date',
            'company.county',
            'company.town',
            'company.building',
            'company.floor',
            'company.telephone_number',
            'company.company_email:email',
            //'type_of_business',
            'company.postal_address',
            'company.company_categorization',
            'accreditationType.name',
            'cash_flow',
            'financial_status_link',
            //'user_id',
            'status',
            [
                'attribute' =>'declaration',
                'value' => function() use($model){
                    return $model->declaration == 1? "Yes" : "No";
                }
            ],            
        ],
    ]) ?>

</div>
<?php
    $xpSearchModel = new \app\models\ApplicationCompanyExperienceSearch();
    $xpSearchModel->application_id = $model->id;
    $xpDataProvider = $xpSearchModel->search([]);
    
    echo $this->render('../application-company-experience/index' ,[
        'dataProvider' => $xpDataProvider,'searchModel'=>$xpSearchModel
    ]);
?>
<?php
    $asSearchModel = new \app\models\ApplicationStaffSearch();
    $asSearchModel->application_id = $model->id;
    $asDataProvider = $asSearchModel->search([]);
    echo $this->render('../application-staff/index' ,[
        'dataProvider' => $asDataProvider,'searchModel'=>$asSearchModel
    ]);    
?>

<?php
    $cdSearchModel = new \app\models\CompanyDocumentSearch();
    $cdSearchModel->company_id = $model->company_id;
    $cdDataProvider = $cdSearchModel->search([]);
    echo $this->render('company_document_grid', [
        'searchModel' => $cdSearchModel,
        'dataProvider' => $cdDataProvider,
    ]);
?>

<?php
    $paymentSearchModel = new \app\models\PaymentSearch();
    $paymentSearchModel->application_id = $model->id;
    $paymentDataProvider = $paymentSearchModel->search([]);
    echo $this->render('payment_grid', [
        'searchModel' => $paymentSearchModel,
        'dataProvider' => $paymentDataProvider,
    ]);
?>

<?php
if(Yii::$app->user->identity->isInternal()){
    $reviewerSearchModel = new \app\models\ApplicationCommitteMemberSearch();
    $reviewerSearchModel->application_id = $model->id;
    $reviewerDataProvider = $reviewerSearchModel->search([]);
    echo $this->render('reviewers_grid', [
        'searchModel' => $reviewerSearchModel,
        'dataProvider' => $reviewerDataProvider,
    ]);
}
?>

<?php
if(Yii::$app->user->identity->isInternal()){
    $acSearchModel = new \app\models\ApplicationClassificationSearch();
    $acSearchModel->application_id = $model->id;
    $acDataProvider = $acSearchModel->search([]);
    echo $this->render('app_classification_grid', [
        'searchModel' => $acSearchModel,
        'dataProvider' => $acDataProvider,
    ]);
}
?>
<?php
$this->registerJsFile('../js/general_js.js', ['position'=>yii\web\View::POS_END]);
$this->registerJsFile('../js/company_staff.js', ['position'=>yii\web\View::POS_END]);
