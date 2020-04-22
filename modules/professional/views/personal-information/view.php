<?php

use kartik\tabs\TabsX;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\professional\models\PersonalInformation */

$this->title = $model->first_name . ' ' . $model->last_name;
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);
?>
<h1><?= Html::encode($this->title) ?></h1>
<div class="personal-information-view">
    <?= TabsX::widget([
    'position' => TabsX::POS_ABOVE,
    'align' => TabsX::ALIGN_LEFT,
    'containerOptions' => ['id'=> 'company_profile_tabs'],
    'items' => [
        [
            'label' => 'Personal Details',
            'content' => $this->render('personal_info_dtl', [
                'model' => $model,
                //'dataProvider' => $dataProvider,
            ]),
            'active' => true
        ],        
        [
            'label' => 'Education Details',
            'linkOptions'=>['data-url'=>\yii\helpers\Url::to(['education/my-education', 'pid'=>$model->id])],
            //'headerOptions' => ['style'=>'font-weight:bold'],
            'options' => ['id' => 'pi_edudata_tab'],
        ],
        [
            'label' => 'Employment Details',
            'linkOptions'=>['data-url'=>\yii\helpers\Url::to(['employment/my-employment', 'pid'=>$model->id])],
            //'headerOptions' => ['style'=>'font-weight:bold'],
            'options' => ['id' => 'pi_empdata_tab'],
        ],
        [
            'label' => 'Profesional Certifications',
            'linkOptions'=>['data-url'=>\yii\helpers\Url::to(['professional-reg-bodies/my-memberships', 'pid'=>$model->id])],
            //'headerOptions' => ['style'=>'font-weight:bold'],
            'options' => ['id' => 'pi_regdata_tab'],
        ],
        [
            'label' => 'Application',
            'linkOptions'=>['data-url'=>\yii\helpers\Url::to(['application/my-application', 'pid'=>$model->id])],
            //'headerOptions' => ['style'=>'font-weight:bold'],
            'options' => ['id' => 'pi_appdata_tab'],
        ],
    ],
]); 
?>

</div>
<?php
$this->registerJsFile(Yii::getAlias('@web'). '/js/general_js.js', ['position'=>yii\web\View::POS_END]);
