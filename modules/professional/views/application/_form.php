<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\modules\professional\models\Application */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="application-form" id="individual_app_div">
    <h3>Refer to the homepage for information on requirements for each category.</h3>
    <hr>

    <?php $form = ActiveForm::begin(['layout'=>'horizontal', 
        'action' =>['application/create-ajax', 'pid'=>$model->user_id],
            'options' => ['enctype' => 'multipart/form-data'
        ]]); ?>

    <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(
            app\modules\professional\models\Category::find()->all(), 'id', 'name')
            , ['prompt' => '']) ?>

    <?= $form->field($model, 'declaration')->checkBox() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 
            'onclick'=>'saveDataForm(this, "individual_app_div"); return false;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
