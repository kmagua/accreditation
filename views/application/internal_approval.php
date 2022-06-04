<?php
/* @var $this yii\web\View */
/* @var $application_scores app\models\ApplicationScore */
/* @var $app_id Application->id */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin();

$current_category = $current_specific_item =  $ac_classification = $app_status = $ac_comment = "";
$ac_score = 0;
$level_val = ($level == 2)?1:$level;
$app_classification = app\models\ApplicationClassification::find()->where(['application_id'=>$app_id, 'icta_committee_id'=>$level_val])->one();
if($level == 2){
    $ac = app\models\ApplicationClassification::find()->where(['application_id'=>$app_id, 'icta_committee_id'=>2])->one();
    if($ac && $ac->score > 0){
        $app_classification = $ac;
    }
}
if($app_classification){
    $ac_score = ($app_classification->score =='')?0:$app_classification->score;
    $ac_classification = $app_classification->classification;
    $app_status = $app_classification->status;
    $ac_comment = $app_classification->rejection_comment;
}
$cur_application = app\models\Application::findOne($app_id);
$renewal_levl = '';
//ONLY FOR Renewals
if($cur_application->application_type == 2){
    if($cur_application->parent_id != ''){
        $category = $cur_application->getLatestCategory();
        $renewal_levl = '<span style="color:red"> Previous was ' . $category .'</span>';
        echo '<h3 style="color:red">THIS IS A RENEWAL. Previous Category was ' . $category . '</h3>';
    }else{
        $renewal_levl = '<span style="color:red"> Previous was ' . $cur_application->previous_category .'(<i>by applicant</i>)</span>';
        echo '<h3 style="color:red">THIS IS A RENEWAL. Previous Category (<i>From Applicant</i>) was ' . $cur_application->previous_category . '</h3>';
    }
}
?>
<div class="row" style="margin-top:30px;">
    <div class="row" style="text-align: center !important;">
        <u>
            <h5>
                <?= $cur_application->company->company_name . '\'s ' . $cur_application->accreditationType->name ?> Accreditation Request
            </h5>
        </u>
    </div>
    
    <div class="col-md-2">
        <h4>Categoty</h4><hr>
    </div>

    <div class="col-md-3">
        <h4>Specific Item</h4><hr>
    </div>

    <div class="col-md-4">
        <h4>Score Item</h4><hr>
    </div>

    <div class="col-md-1">
        <h4>Score</h4><hr>
    </div>
    <div class="col-md-2">
        <h4>Comment</h4><hr>
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
            <?php if($application_score->scoreItem->content_link != ''){
                echo Html::a($application_score->scoreItem->category, 
                        ['/application/get-data', 'id' => $app_id, 
                            'sec'=>$application_score->scoreItem->content_link], 
                        ['onclick' => 'getDataForm(this.href, "<h3>' . $application_score->scoreItem->category. '</h3>");return false;']);
            }else{
                echo $application_score->scoreItem->category;
            }
            ?>
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
        
        <div class="col-md-4">
            <?= $application_score->scoreItem->score_item ?>
        </div>
        
        <div class="col-md-1">
            <?php
            $class = ($application_score->scoreItem->group != '')? ['class' => $application_score->scoreItem->group]:[];
            if($application_score->scoreItem->checkboxes > 1){
                $upper = $application_score->scoreItem->checkboxes * $application_score->scoreItem->each_checkbox_marks;
                $init_array = range(0, $upper, $application_score->scoreItem->each_checkbox_marks);
                $array = array_combine($init_array, $init_array);
                //echo 'ahap'. $application_score->score; 
                //echo $form->field($application_score, "[$index]score")->radioList($array)->label(false);
                echo Html::radioList("ApplicationScore[$index][score]", 
                    $application_score->score , $array, ['id' => "applicationscore-{$index}-score"]);
            }else{
                //echo '<pre>'; print_r($application_score[$index]); exit;
                echo Html::checkbox("ApplicationScore[$index][score]", 
                    ($application_score->score > 0)?true: false , ['id' => "applicationscore-{$index}-score", 'class'=>$class]);
                //echo 'Hapa', $application_score->score;
                //echo $form->field($application_score, "[$index]score")->checkbox($class)->label(false);
            }            
            ?>
            <?= ""/// Html::checkbox("ApplicationScore[score][".$application_score['sc_id'] ."]" , null) ?>
            <?= $form->field($application_score, "[$index]maximum_score")->hiddenInput(['value' => $application_score->scoreItem->maximum_score])->label(false); ?>
        </div>
    
        <div class="col-md-2">
            <?php
            //$class = ($application_score->scoreItem->group != '')? ['class' => $application_score->scoreItem->group]:[];\               
            echo $form->field($application_score, "[$index]comment")->textarea()->label(false);
            ?>
            <?= ""/// Html::checkbox("ApplicationScore[score][".$application_score['sc_id'] ."]" , null) ?>
            <?= $form->field($application_score, "[$index]maximum_score")->hiddenInput(['value' => $application_score->scoreItem->maximum_score])->label(false); ?>
        </div>
</div>
<?php
}
?>

<div class ="row" style="margin-top: 10px; margin-bottom: 10px ">
    <div class="col-md-4">
        <?= Html::label("Score", 'applicationscore-committee_score') ?>
        <?= Html::textInput("ApplicationScore[committee_score]", $ac_score, ['class'=>'form-control','id'=>'applicationscore-committee_score',
            'readonly'=>true]); ?>
    </div>
    <div class="col-md-5">
        <?= Html::label("Category Assigned $renewal_levl", 'applicationscore-classification') ?>
        <?=Html::dropDownList("ApplicationScore[classification]", $ac_classification, [
            'ICTA 1' => 'ICTA 1', 'ICTA 2' => 'ICTA 2', 'ICTA 3' => 'ICTA 3', 'ICTA 4' => 'ICTA 4',
            'ICTA 5' => 'ICTA 5', 'ICTA 6' => 'ICTA 6', 'ICTA 7' => 'ICTA 7', 'ICTA 8' => 'ICTA 8',
            'reapply' => 'Re-apply'
        ], ['prompt' => '', 'class' => 'form-control', 'id' => 'applicationscore-classification',
            'readonly'=>true, 'style'=>'pointer-events: none;']) ?>
    </div>
    
    
</div>

<div class="row">
    <div class="col-md-4">
        <?= Html::label("Approval Status", 'applicationscore-status') ?>
        <?= Html::dropDownList("ApplicationScore[status]", $app_status, [
            1 => 'Approved', 0 => 'Rejected'
        ], ['prompt' => '', 'class' => 'form-control', 'id' => 'applicationscore-status'
            //'readonly'=>true, 'style'=>'pointer-events: none;'
            ]) ?>
    </div>
    
    <div class="col-md-5">
        <?= Html::label("Comment", 'applicationscore-rejection_comment') ?>
        <?= Html::textarea("ApplicationScore[rejection_comment]", $ac_comment, ['class' => 'form-control', 'id' => 'applicationscore-rejection_comment']) ?>
    </div>
</div>


<div class="form-group">
    <?= Html::submitButton('Submit', ['class' => 'btn btn-success', 'onclick' => 'return validateForm(); ']) ?>
</div>

<?php
ActiveForm::end();

$js = <<<JS
var refresh_on_close = false;
$( document ).ready(function() {
    var score = $ac_score;
        
    $( "input:checkbox" ).click(function(){
        scored = Number($('#applicationscore-committee_score').val());
        var element_details_array = this.id.split("-");
        var applicable_score = $("#"+element_details_array[0] +"-"+element_details_array[1] + "-maximum_score").val();
        
        if(this.checked == true){
            scored += Number(applicable_score);
            $('#applicationscore-committee_score').val(scored); //update value first
            clearInSimilarClass(this)
        }else{
            //console.log(element_details_array)
            //alert(applicable_score)
            scored -= Number(applicable_score);
        }
        $('#applicationscore-committee_score').val(scored);
        calculateCategoty(scored);
    });
        
    
        
    $( "input:radio" ).mouseup(function(){        
        if($('input[name="' + this.name+'"]:checked').val() != null){
            sco = Number($('#applicationscore-committee_score').val());
            sco -= Number($('input[name="' + this.name+'"]:checked').val());
            $('#applicationscore-committee_score').val(sco);
        }
    }).change(function(){
        sc = Number($('#applicationscore-committee_score').val());
        
        sc += Number($('input[name="'+ this.name + '"]:checked').val());
        $('#applicationscore-committee_score').val(sc);
        calculateCategoty(sc);
    });
        
    
});
JS;
$this->registerJs($js,yii\web\View::POS_END, 'calculate_application_score');
$this->registerJsFile('../js/application_scoring.js', ['position'=>yii\web\View::POS_END]);
$this->registerJsFile('../js/general_js.js', ['position'=>yii\web\View::POS_END]);