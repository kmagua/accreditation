<?php

use kartik\tabs\TabsX;
use app\models\AcademicQualificationSearch;
use app\models\StaffExperienceSearch;
use app\models\ProfessionalCertificationSearch;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyStaff */

\yii\web\YiiAsset::register($this);

// acasemic qualifications
$acSearchModel = new AcademicQualificationSearch();
$acSearchModel->staff_id = $model->id;
$acDataProvider = $acSearchModel->search(Yii::$app->request->queryParams);

// acasemic qualifications
$pcSearchModel = new ProfessionalCertificationSearch();
$pcSearchModel->staff_id = $model->id;
$pcDataProvider = $pcSearchModel->search(Yii::$app->request->queryParams);

// acasemic qualifications
$xpSearchModel = new StaffExperienceSearch();
$xpSearchModel->staff_id = $model->id;
$xpDataProvider = $xpSearchModel->search(Yii::$app->request->queryParams);
?>
<div class="company-staff-view">
<?= $model->first_name . ' ' . $model->last_name .' (' . $model->staff_type . ')'?>
<?= TabsX::widget([
    'items' => [
        [
            'label' => 'Personal Information',
            'options' => ['id' => 'staff-personal-details-tab' . $model->id],
            'content' => $this->render('_view', ['model'=>$model]),
        ],
        [
            'label' => 'Academic Qualifications',
            'options' => ['id' => 'staff-academic-qualifications-tab_' . $model->id],
            'content' => $this->render('../academic-qualification/gridview', 
                ['searchModel'=>$acSearchModel, 'dataProvider'=>$acDataProvider]
            ),
        ],
        [
            'label' => 'Professional Certifications',
            'options' => ['id' => 'staff-professional-certifications-tab' . $model->id],
            'content' => $this->render('../professional-certification/gridview', 
                ['searchModel'=>$pcSearchModel, 'dataProvider'=>$pcDataProvider]
            ),
        ],
        [
            'label' => 'Work Experience',
            'options' => ['id' => 'staff-staff-experience-tab' . $model->id],
            'content' => $this->render('../staff-experience/gridview', 
                ['searchModel'=>$xpSearchModel, 'dataProvider'=>$xpDataProvider]
            ),
        ],
    ],
    'position'=>TabsX::POS_LEFT,
    'encodeLabels'=>false,
    'containerOptions' =>['id' => 'full-staff-details-view'],
    /*'options' => ['tag' => 'div'],
    'itemOptions' => ['tag' => 'div'],
    'headerOptions' => ['tag' => 'h3'],
    'clientOptions' => ['collapsible' => true],*/
]);

?>

</div>
