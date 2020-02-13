<?php
use yii\helpers\Html;
use kartik\tabs\TabsX;
use kartik\icons\Icon;

Icon::map($this, Icon::FAS); // Maps the Elusive icon font framework

/* @var $this yii\web\View */
/* @var $model app\models\CompanyProfile */

$this->title = $model->company_name;
$this->params['breadcrumbs'][] = ['label' => 'Company Profiles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="company-profile-view">

<h1><?= Html::encode($this->title) ?></h1>

<?= TabsX::widget([
    'position' => TabsX::POS_ABOVE,
    'align' => TabsX::ALIGN_LEFT,
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
            'label' => 'Staff Details',
            'linkOptions'=>['data-url'=>\yii\helpers\Url::to(['company-staff/staff-data', 'cid'=>$model->id])],
            'headerOptions' => ['style'=>'font-weight:bold'],
            'options' => ['id' => 'myveryownID'],
        ],
        [
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
        ],
    ],
]); 
?>
</div>
<?php
$this->registerJsFile('../js/general_js.js', ['position'=>yii\web\View::POS_END]);
$this->registerJsFile('../js/company_staff.js', ['position'=>yii\web\View::POS_END]);