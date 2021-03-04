<?php
use yii\helpers\Html;
?>

<?= Html::beginForm(['application/review-after-petition', 'id' => $id], 'post', ['enctype' => 'multipart/form-data']) ?>
<p></p>
<p></p>
<p></p>
<h3><?= $model->company->company_name ."'s  application for: " . $model->accreditationType->name ?></h3>
<?= Html::label('Tick the tickbox below to revert this application for re-consideration') ?><br/>
<?= Html::input('checkbox', 'confirm', null , ['class' => 'input-control', 'required'=>true]) ?><br/>
<?= Html::input('submit', "revert", "Revert to Committee", ['onclick'=>"return confirm('Are you sure you want to revert this for reconsideration on previous levels?')", 'class' => 'btn btn-primary']) ?>


<?= Html::endForm() ?>
