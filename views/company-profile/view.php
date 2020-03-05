<?php
use yii\helpers\Html;
use kartik\tabs\TabsX;
use kartik\icons\Icon;
//use kartik\select2\Select2;
\kartik\select2\Select2Asset::register($this);

Icon::map($this, Icon::FAS); // Maps the Elusive icon font framework

/* @var $this yii\web\View */
/* @var $model app\models\CompanyProfile */

$this->title = $model->company_name;
$this->params['breadcrumbs'][] = ['label' => 'Company Profiles',
    'url' => \Yii::$app->user->identity->isInternal()?['index']:['my-companies']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$searchCD = new app\models\CompanyDocumentSearch();
$searchCD->company_id = $model->id;
$cdDataProvider = $searchCD->search([]);

$searchCP = new app\models\CompanyExperienceSearch();
$searchCP->company_id = $model->id;
$cpDataProvider = $searchCP->search([]);
?>
<div class="company-profile-view">

<h1><?= Html::encode($this->title) ?></h1>

<?= TabsX::widget([
    'position' => TabsX::POS_ABOVE,
    'align' => TabsX::ALIGN_LEFT,
    'containerOptions' => ['id'=> 'company_profile_tabs'],
    'items' => [
        [
            'label' => 'Company Details',
            'content' => $this->render('_companydetail', [
                'model' => $model,
                //'dataProvider' => $dataProvider,
            ]),
            'active' => true
        ],
       [
            'label' => 'Company Documents',
            'content' => $this->render('../company-document/index', [
                'searchModel' => $searchCD,
                'dataProvider' => $cdDataProvider,
            ]),
            
        ],
       [
            'label' => 'Company Projects/Experience',
            'content' => $this->render('../company-experience/index', [
                'searchModel' => $searchCP,
                'dataProvider' => $cpDataProvider,
            ]),
            
        ],
        [
            'label' => 'Staff Details',
            'linkOptions'=>['data-url'=>\yii\helpers\Url::to(['company-staff/staff-data', 'cid'=>$model->id])],
            'headerOptions' => ['style'=>'font-weight:bold'],
            'options' => ['id' => 'company_data_tab'],
        ],
        [
            'label' => 'Applications',
            'linkOptions'=>['data-url'=>\yii\helpers\Url::to(['application/applications', 'cid'=>$model->id])],
            'headerOptions' => ['style'=>'font-weight:bold'],
            'options' => ['id' => 'application_data_tab'],
        ],
        /*[
            'label' => 'Dropdown',
            'items' => [
                 [
                     'label' => 'DropdownA',
                     'content' => 'DropdownA, Anim pariatur cliche...',
                 ],
                 [
                     'label' => 'DropdownB',
                     'content' => 'DropdownB, Anim pariatur cliche...',
                 ],
            ],
        ],*/
    ],
]); 
?>
</div>
<?php
$this->registerJsFile('../js/general_js.js', ['position'=>yii\web\View::POS_END]);
$js = <<<JS
$( document ).ready(function() {
    $(function () {        
        $('#company_profile_tabs a[href="' + location.hash + '"]').tab('show');
    });
        
});
JS;
$this->registerJs($js,yii\web\View::POS_END, 'enable_select2');

