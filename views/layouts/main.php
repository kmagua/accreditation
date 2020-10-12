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
    
     <?php $this->registerCssFile("/css/theming.css") ?>
    
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

    <div class="wrap">
    <?php   
    NavBar::begin([
        'brandImage' => Yii::getAlias("@web")."/images/ictabanntransparentlast.png",
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
            ['label' => 'Home', 'url' => ['/site/index'],'linkOptions' => ['style' => 'color: white;font-weight: 450;margin-top: 20px;'],],
            /*['label' => 'Applications', 'url' => ['/application/index'],
                'visible'=> (!Yii::$app->user->isGuest && Yii::$app->user->identity->isInternal()),
                'linkOptions' => ['style' => 'color: white;font-weight: 900;margin-top: 25px;']],*/
            
            [
                'label' => 'Applications', 'linkOptions' => ['style' => 'color: white;font-weight: 450;margin-top: 20px;'],
                'items' => [
                    ['label' => 'Company Accreditation', 'url' => ['/application/index'],
                        //'visible'=> (!Yii::$app->user->isGuest && Yii::$app->user->identity->isInternal()),
                        'linkOptions' => ['style' => 'color: white;font-weight: 450; background-color:green']],
                    ['label' => 'My Pending Reviews', 'url' => ['/application/my-assigned'],
                        //'visible'=> (!Yii::$app->user->isGuest && Yii::$app->user->identity->isInternal()),
                        'linkOptions' => ['style' => 'color: white;font-weight: 450;background-color:green']],
                    
                ],
                'visible'=> (!Yii::$app->user->isGuest && Yii::$app->user->identity->isInternal())
            ],
            [
                'label' => 'Quick Links', 'linkOptions' => ['style' => 'color: white;font-weight: 450;margin-top: 20px;'],
                'items' => [
                    ['label' => 'Company Profile', 'url' => ['/company-profile/create'],                        
                        'linkOptions' => ['style' => 'color:#fff; background-color:green']],
                    //['label' => 'Professional', 'url' => ['/professional/personal-information/my-profile'],
                    //    'linkOptions' => ['style' => 'color: white;font-weight: 450;background-color:green']],
                    ['label' => 'My Account', 'url' => ['/user/my-profile'],
                        'linkOptions' => ['style' => 'color:#fff; background-color:green']],
                    ['label' => 'FAQs', 'url' => ['/site/faqs'],
                        'linkOptions' => ['style' => 'color:#fff; background-color:green']],
                    ['label' => 'Change My Password', 'url' => ['/user/change-password'],
                        'linkOptions' => ['style' => 'color: white;font-weight: 450;background-color:green']],
                ],
                'visible'=> (!Yii::$app->user->isGuest && Yii::$app->user->identity->inGroup('Applicant', false))
            ],
            [
                'label' => 'Administration', 'linkOptions' => ['style' => 'color: white;font-weight: 450;margin-top: 20px;'],
                'items' => [
                    ['label' => 'Users', 'url' => ['/user/index'],                        
                        'linkOptions' => ['style' => 'color:#fff; background-color:green']],
                    ['label' => 'List of Application documents', 'url' => ['/document-type/index'],
                        'linkOptions' => ['style' => 'color:#fff; background-color:green']],
                    ['label' => 'Accreditation Levels', 'url' => ['/accreditation-level/index'],
                        'linkOptions' => ['style' => 'color:#fff; background-color:green']],
                    ['label' => 'Accreditation Categories', 'url' => ['/accreditation-type/index'],
                        'linkOptions' => ['style' => 'color:#fff; background-color:green']],
                    ['label' => 'Approval Stages', 'url' => ['/icta-committee/index'],
                        'linkOptions' => ['style' => 'color:#fff; background-color:green']],
                    ['label' => 'Reports', 'url' => ['/site/reports'],
                        'linkOptions' => ['style' => 'color:#fff; background-color:green']],
                    ['label' => 'FAQs', 'url' => ['/site/faqs'],
                        'linkOptions' => ['style' => 'color: white;font-weight: 450;background-color:green']],
                    ['label' => 'Change My Password', 'url' => ['/user/change-password'],
                        'linkOptions' => ['style' => 'color: white;font-weight: 450;background-color:green']],
                ],
                'visible'=> (!Yii::$app->user->isGuest && Yii::$app->user->identity->isInternal())
            ],
            ['label' => 'Register', 'url' => ['/user/register'],
                'linkOptions' => ['style' => 'color: white;font-weight: 450;margin-top: 20px;'],'visible'=>Yii::$app->user->isGuest],
            
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login'],'linkOptions' => ['style' => 'color: white;font-weight: 450;margin-top: 20px;']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout',
                    ['class' => 'btn btn-link logout glyphicon glyphicon-log-out', 'style' => 'color: white;font-weight: 450;margin-top: 20px;']
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
        <?= \mgcode\sessionWarning\widgets\SessionWarningWidget::widget([
            'warnBefore' => 240,            
        ]); ?>
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
echo "<div id='modalContent'><div style='text-align:center'></div></div>";
echo '<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close Dialog</button>
      </div>';
yii\bootstrap\Modal::end();
?>
<?php $this->endBody() ?>

<?php
$path = Yii::getAlias('@web');
$js = <<<JS
var basePathWeb = '$path'
$('#accreditation-modal').on('hidden.bs.modal', function () {
    if (typeof refresh_on_close === 'undefined') {
        location.reload();
    }
})

JS;

$this->registerJs($js,yii\web\View::POS_END, 'refresh_on_close_modal');
?>
</body>
</html>
<?php $this->endPage() ?>
