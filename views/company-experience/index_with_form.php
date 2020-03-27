<?php

/*use yii\helpers\Html;
use yii\grid\GridView;*/

/* @var $this yii\web\View */
/* @var $searchModel app\models\CompanyExperienceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$model = new \app\models\CompanyExperience();
$model->company_id = $searchModel->company_id;
?>
<div class="company-experience-index">

    <?= $this->render('_form', [
        'model' => $model,
    ]);
    ?>
</div>
