<?php

use yii\helpers\Html;

$this->title = "Accreditation System Reports";
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= Html::a('1. Accredited Suppliers', ['/application/accredited-suppliers'], 
            ['class' => 'btn btn-primary', 'title' =>"Original Applications", 'style' => 'margin-bottom:5px']); ?>
    <br>
    <?= Html::a('2. Accredited Individuals', ['/professional/application/accredited-professionals'], 
            ['class' => 'btn btn-primary', 'title' =>"Original Applications", 'style' => 'margin-bottom:5px']); ?>
    <br>
    <?= Html::a('3. Supplier Application Statuses', ['/application/statuses-report'], 
        ['class' => 'btn btn-primary', 'title' =>"Supplier Application Statuses", 'style' => 'margin-bottom:5px']); ?>
    <br>
    <?= Html::a('4. Professional Application Statuses', ['/professional/application/statuses-report'], 
        ['class' => 'btn btn-primary', 'title' =>"Original Applications", 'style' => 'margin-bottom:5px']); ?>
    <br>
    <?= Html::a('5. Review by Internal Staff List', ['/application/review-report-by-staff'], 
        ['class' => 'btn btn-primary', 'title' =>"Review by Internal Staff List", 'style' => 'margin-bottom:5px']); ?>
    <br>
    <?= Html::a('6. Payments Report', ['/application/payment-report'], 
        ['class' => 'btn btn-primary', 'title' =>"Payments Report", 'style' => 'margin-bottom:5px']); ?>

</div>
