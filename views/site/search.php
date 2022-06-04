<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = "Validate certificate";
?>


<div class="site-login">
    <div class="row" style="margin-top: 20px">
    <p>Please fill in the certificate serial number to search:</p>
    </div>
    <?php $form = ActiveForm::begin([
        'id' => 'validate-cert-form',        
    ]); ?>
    <div class="row">
        <?= Html::textInput('certificate_no', null, ['class' => 'form-control', 
            'id'=>'certificate_no', 'placeholder' => 'Certificate serial number', 'pattern' => "[a-zA-Z0-9]+", 'title' => "Certificate Serial Number should only contain alphanumerics"]) ?>
    </div>
       
    <div class="form-group row" style="margin-top: 5px">            
        <?= Html::submitButton($this->title, ['class' => 'btn btn-success', 'name' => 'validate-button']) ?>            
    </div>

    <?php ActiveForm::end(); ?>
    
    <div class="row">
        <?php if(Yii::$app->session->hasFlash('cert_search')): ?>
        <div class="alert alert-success alert-dismissable">
            <h4><?php echo Yii::$app->session->getFlash('cert_search'); ?></h4>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- <h4>You can also search from the list of Companies Accredited before system was rolled out <?= '' //Html::a('HERE', ['pre-system-accredited-companies/index']) ?></h4> -->
