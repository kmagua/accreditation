<?php

use yii\helpers\Html;

$this->title = "Accreditation System Reports";
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= Html::a('1. Accredited Suppliers', ['/application/accredited-suppliers'], 
            ['class' => 'btn btn-primary', 'title' =>"Original Applications", 'style' => 'margin-bottom:5px']); ?>
    <br>
    <?= Html::a('2. Accredited Individuals', ['/application/accredited-suppliers'], 
            ['class' => 'btn btn-primary', 'title' =>"Original Applications"]); ?>

</div>
