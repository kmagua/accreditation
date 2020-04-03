<?php

/*use yii\helpers\Html;
use yii\grid\GridView;*/

/* @var $this yii\web\View */
/* @var $searchModel app\models\CompanyExperienceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
if(!isset($model)){
    $model = new \app\models\CompanyExperience();
    $model->company_id = $searchModel->company_id;
    $model->setScenario('create');
}
?>
<div class="company-experience-index">

    <?= $this->render('_form', [
        'model' => $model,
    ]);
    ?>
</div>
