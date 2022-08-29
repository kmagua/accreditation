<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyProfile */

$this->title = 'Company Profile';
/*$this->params['breadcrumbs'][] = ['label' => 'Company Profiles',
    'url' => \Yii::$app->user->identity->isInternal()?['index']:['my-companies']];*/
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