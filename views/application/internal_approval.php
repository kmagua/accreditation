<?php
/* @var $this yii\web\View */
/* @var $application_scores app\models\ApplicationScore */
/* @var $app_id Application->id */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
$form = ActiveForm::begin();

$current_category = $current_specific_item =  $ac_classification = "";
$ac_score =0;
$app_classification = app\models\ApplicationClassification::find()->where(['application_id'=>$app_id, 'icta_committee_id'=>$level])->one();
if($app_classification){    
    $ac_score = $app_classification->score;
    $ac_classification = $app_classification->classification;
}
?>
<div class="row" style="margin-top:30px;">
    <div class="col-md-2">
        <h2>Categoty</h2><hr>
    </div>

    <div class="col-md-3">
        <h2>Specific Item</h2><hr>
    </div>

    <div class="col-md-5">
        <h2>Score Item</h2><hr>
    </div>

    <div class="col-md-2">
        <h2>Score</h2><hr>
    </div>
</div>


<?php
foreach ($application_scores as $index => $application_score) {
    if($this->title ==''){
        $this->title = "Company Accreditation - " . $application_score->committee->name;
    }
?>

<div class="row">    
        <div class="col-md-2">
            <?php
            if($current_category != $application_score->scoreItem->category){
                $current_category = $application_score->scoreItem->category;
            ?>
            <?= $application_score->scoreItem->category ?>
            <?php } ?>
        </div>
        
        <div class="col-md-3">
             <?php
            if($current_specific_item != $application_score->scoreItem->specific_item){
                $current_specific_item = $application_score->scoreItem->specific_item;
            ?>
            <?= $application_score->scoreItem->specific_item ?>
            <?php } ?>
        </div>
        
        <div class="col-md-5">
            <?= $application_score->scoreItem->score_item ?>
        </div>
        
        <div class="col-md-2">
            <?php
            $class = ($application_score->scoreItem->group != '')? ['class' => $application_score->scoreItem->group]:[];
            if($application_score->scoreItem->checkboxes > 1){
                $upper = $application_score->scoreItem->checkboxes * $application_score->scoreItem->each_checkbox_marks;
                $init_array = range(0, $upper, $application_score->scoreItem->each_checkbox_marks);
                $array = array_combine($init_array, $init_array);
                
                echo $form->field($application_score, "[$index]score")->radioList($array)->label(false);
            }else{
                echo $form->field($application_score, "[$index]score")->checkbox($class)->label(false);
            }            
            ?>
            <?= ""// Html::checkbox("ApplicationScore[score][".$application_score['sc_id'] ."]" , null) ?>
            <?= $form->field($application_score, "[$index]maximum_score")->hiddenInput(['value' => $application_score->scoreItem->maximum_score])->label(false); ?>
        </div>
</div>
<?php
}
?>
<div class ="row" style="margin-top: 10px; margin-bottom: 10px ">
    <div class="col-md-4">
        <?= Html::label("Score", 'applicationscore-committee_score') ?>
        <?= Html::textInput("ApplicationScore[committee_score]", $ac_score, ['class'=>'form-control','id'=>'applicationscore-committee_score']); ?>
    </div>
    <div class="col-md-5">
        <?= Html::label("Category Assigned", 'applicationscore-classification') ?>
        <?=Html::dropDownList("ApplicationScore[classification]", $ac_classification, [
            'ICTA 1' => 'ICTA 1', 'ICTA 2' => 'ICTA 2', 'ICTA 3' => 'ICTA 3', 'ICTA 4' => 'ICTA 4',
            'ICTA 5' => 'ICTA 5', 'ICTA 6' => 'ICTA 6', 'ICTA 7' => 'ICTA 7', 'ICTA 8' => 'ICTA 8',
            'reapply' => 'Re-apply'
        ], ['prompt' => '', 'class' => 'form-control', 'id' => 'applicationscore-classification']) ?>
    </div>
    
    <div class="col-md-3">
        <?= Html::label("Approval Status", 'applicationscore-status') ?>
        <?=Html::dropDownList("ApplicationScore[status]", '', [
            1 => 'Approved', 0 => 'Rejected'
        ], ['prompt' => '', 'class' => 'form-control', 'id' => 'applicationscore-status']) ?>
    </div>
</div>
<div class="form-group">
    <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
</div>

<?php
ActiveForm::end();

$js = <<<JS
$( document ).ready(function() {
    var score = $ac_score;
        
    $( "input:checkbox" ).click(function(){        
        var element_details_array = this.id.split("-");
        var applicable_score = $("#"+element_details_array[0] +"-"+element_details_array[1] + "-maximum_score").val();
        if(this.checked == true){
            score += Number(applicable_score);
            clearInSimilarClass(this, score)
        }else{
            score -= Number(applicable_score);
        }        
        $('#applicationscore-committee_score').val(score);        
        calculateCategoty(score);
    });        
});
JS;
$this->registerJs($js,yii\web\View::POS_END, 'calculate_application_score');
$this->registerJsFile('../js/application_scoring.js', ['position'=>yii\web\View::POS_END]);