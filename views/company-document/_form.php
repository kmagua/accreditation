<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\CompanyTypeDocument;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyDocument */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="company-document-form">    
        
    <?php $form = ActiveForm::begin(['id' =>'company-document-form',
        'action' => ($model->isNewRecord) ? ['company-document/create-ajax', 'cid'=>$model->company_id] : 
            ['company-document/update-ajax', 'id'=>$model->id],
            'options' => ['enctype' => 'multipart/form-data']]) ?>
    
    <div class="row"> 
        <div class="col-md-4">
            <?= $form->field($model, 'company_type_doc_id')->dropDownList(
             ArrayHelper::map(CompanyTypeDocument::getApplicableDocumentTypes($model->company->company_type_id),'id', 'name'), ['prompt'=>'']) ?>
        </div>
         
        <div class="col-md-8">    
            <?= $form->field($model, 'uploadFile')->fileInput() ?>
        </div>
    </div>
 

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success',
            'onclick'=>'saveDataForm(this); return false;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
