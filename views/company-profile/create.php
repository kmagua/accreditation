<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyProfile */






$this->title = 'Create Company Profile';
$this->params['breadcrumbs'][] = ['label' => 'Company Profiles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?> 

<div class="company-profile-create">
   
<div class="bordered">
    <h2 style=" color: green"><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
 </div>

<style>
  .bordered {
    width: 1200px;
    height: 600px;
    padding: 20px;
    border: 1px solid darkorange;
    border-radius: 8px;
  }
</style>



