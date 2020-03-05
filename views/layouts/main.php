<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    
     <?php $this->registerCssFile("css/theming.css") ?>
    
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

    <div class="wrap">
      
       
        
    <?php
   
    NavBar::begin([
        'brandImage' => Yii::getAlias("@web")."/images/ictabanntransparent2.png",
        //'brandLabel' => Yii::$app->name, 
        //'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'my-navbar navbar-fixed-top',            
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right',],
       // 'options' => ['style' => 'forecolor-color: red;'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index'],'linkOptions' => ['style' => 'color: white;font-weight: 900;margin-top: 25px;'],],
            ['label' => 'Applications', 'url' => ['/application/index'],
                'visible'=> (!Yii::$app->user->isGuest && Yii::$app->user->identity->isInternal()),
                'linkOptions' => ['style' => 'color: white;font-weight: 900;margin-top: 25px;']],
            ['label' => 'Register', 'url' => ['/user/register'],
                'linkOptions' => ['style' => 'color: white;font-weight: 900;margin-top: 25px;'],'visible'=>Yii::$app->user->isGuest],
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login'],'linkOptions' => ['style' => 'color: white;font-weight: 900;margin-top: 25px;']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->full_name . ')',
                    ['class' => 'btn btn-link logout', 'style' => 'color: white;font-weight: 900;margin-top: 25px;']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>
        
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; ICT Authority <?= date('Y') ?></p>

        <!--<p class="pull-right"><?= ""//Yii::powered() ?></p>-->
    </div>
</footer>
<?php
yii\bootstrap\Modal::begin([
    'headerOptions' => ['id' => 'modalHeader'],
    'id' => 'accreditation-modal',
    'size' => 'modal-lg',
    //'tabindex' => false,
    'closeButton' => [
        'id'=>'close-button',
        'class'=>'close',
        'data-dismiss' =>'modal',
    ],
    //keeps from closing modal with esc key or by clicking out of the modal.
    // user must click cancel or X to close
    'options' => [
        'data-backdrop' => 'static', 'keyboard' => true,
        'tabindex' => false
    ]
]);
echo "<div id='modalContent'><div style='text-align:center'>" . Html::img('@web/images/radio.gif')  . "</div></div>";
echo '<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>';
yii\bootstrap\Modal::end();
?>
<?php $this->endBody() ?>
<?= Yii::$app->request->absoluteUrl ?>
<?php
$js = <<<JS
$('#accreditation-modal').on('hidden.bs.modal', function () {
    location.reload();
})
JS;

$this->registerJs($js,yii\web\View::POS_END, 'refresh_on_close_modal');
?>
</body>
</html>
<?php $this->endPage() ?>
