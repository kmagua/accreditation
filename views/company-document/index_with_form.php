<?php
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CompanyDocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$model = new \app\models\CompanyDocument();
$model->company_id = $searchModel->company_id;

?>
<div class="company-document-index">
    <?= $this->render('_form', [
        'model' => $model,
    ]);
    ?>
   
</div>
