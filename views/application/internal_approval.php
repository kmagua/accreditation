<?php
/* @var $this yii\web\View */
/* @var $application_scores app\models\ApplicationScore */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin();
$current_category = "";
foreach ($application_scores as $index => $application_score) {
    //print_r($application_score);exit;
?>
<div class="row">    
        <div class="col-md-2">
            <?php
            if($current_category != $application_score['category']){
                $current_category = $application_score['category'];
            ?>
            <?= Html::label($application_score['category']) ?>
            <?php } ?>
        </div>
        
        <div class="col-md-2">
            <?= Html::label($application_score['specific_item']) ?>
        </div>
        
        <div class="col-md-6">
            <?= Html::label($application_score['score_item']) ?>
        </div>
        
        <div class="col-md-2">
            <?= Html::checkbox("ApplicationScore[score][".$application_score['sc_id'] ."]" , null) ?>
            <?= Html::hiddenInput("ApplicationScore[maximum_score][".$application_score['sc_id'] ."]", $application_score['maximum_score']) ?>
        </div>
</div>
<?php
}

ActiveForm::end();