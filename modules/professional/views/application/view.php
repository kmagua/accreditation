<?php
use kartik\tabs\TabsX;
use yii\helpers\Html;
use app\modules\professional\models\ApprovalSearch;

$this->registerCss("
    table.detail-view th {
	width: 35%;
    }");

/* @var $this yii\web\View */
/* @var $model app\modules\professional\models\Application */

$this->title = $model->user->first_name . ' ' . $model->user->last_name;
$this->params['breadcrumbs'][] = ['label' => 'Applications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="application-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= TabsX::widget([
    'position' => TabsX::POS_ABOVE,
    'align' => TabsX::ALIGN_LEFT,
    'containerOptions' => ['id'=> 'company_profile_tabs'],
    'items' => [
        [
            'label' => 'Application Details',
            'content' => $this->render('_view', [
                'model' => $model,
                //'dataProvider' => $dataProvider,
            ]),
            'active' => true
        ],        
        [
            'label' => 'Approvals',
            'content' => $this->render('../approval/index_approve', [
                'model' => $model,
                'dataProvider' => (new ApprovalSearch(['application_id' => $model->id]))->search([]),
            ]),
            'visible' => Yii::$app->user->identity->isInternal()
        ],
    ],
]); 
?>
</div>
<?php
$this->registerJsFile(Yii::getAlias('@web'). '/js/general_js.js', ['position'=>yii\web\View::POS_END]);
