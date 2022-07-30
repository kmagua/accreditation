<?php
use dmstr\widgets\Alert;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
$this->title = $this->title;
dmstr\web\AdminLteAsset::register($this);
$css_url = Yii::getAlias('@web') .'/css/accessibility.css';
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Ionicons -->
    <link href="//code.ionicframework.com/ionicons/1.5.2/css/ionicons.min.css" rel="stylesheet" type="text/css"/>
    <?php $this->registerCss(".navbar-nav > .user-menu > .dropdown-menu > li.user-header {
        height: 70px !important;
      }
      ");
    ?>
    
    <?php $this->registerCssFile($css_url); ?>
    <!-- Theme style -->
    <?php $this->head() ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>

<body class="hold-transition skin-black sidebar-mini">
<?php $this->beginBody() ?>
    <a href="#main-content-div" class="skip" style='color:#B2210E'>Skip to main content</a>
<div class="wrapper">

    <header class="main-header" style="background-color: green">
        <!-- Logo -->
        <a href="<?= \Yii::$app->homeUrl ?>" class="logo" style="background-color: green">
        
         <?php echo Html::img('@web/images/ictalogo.png',[ 'alt' => 'ICT Authority Logo','width' => '250px', 'height' => '50px' ]); ?>
        <?= getenv('APP_TITLE') ?></a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation" style="background-color: green">
            <!-- Sidebar toggle button-->
            <button name="toggle-sidebar" aria-label="toggle-sidebar" class="sidebar-toggle" data-toggle="push-menu" role="button">
<!--                <span class="sr-only">Toggle navigation</span>-->
          
            </button>
            <strong style="color:white; text-align:center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ICT Supplier Accreditation Portal</strong>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                        <!-- Messages: style can be found in dropdown.less-->
                        
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle accessible_toggle-contrast" data-toggle="dropdown" style="color:white">
                                <i class="glyphicon glyphicon-user"></i>
                                <span>User <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header bg-red-active">
                                    
                                    <p style="color:#fff">
                                        <?php $email = '';
                                        if(!Yii::$app->user->isGuest){ 
                                            echo Yii::$app->user->identity->first_name . ' ' 
                                        .Yii::$app->user->identity->last_name;
                                            $email = Yii::$app->user->identity->email; } ?>
                                        <small><?= $email ?></small>
                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <?php if(!Yii::$app->user->isGuest){  ?>
                                        <a href="<?= \yii\helpers\Url::to(['/user/my-profile']) ?>"
                                           class="btn btn-default btn-flat">Profile</a>
                                        <?php } ?>
                                    </div>
                                    <div class="pull-right">
                                        <?php if(!Yii::$app->user->isGuest){  ?>
                                        <a href="<?= \yii\helpers\Url::to(['/site/logout']) ?>"
                                           class="btn btn-default btn-flat" data-method="post">Sign out</a>
                                        <?php } else { ?>
                                        <a href="<?= \yii\helpers\Url::to(['/site/login']) ?>"
                                           class="btn btn-default btn-flat">Login</a><?php } ?>
                                    </div>
                                </li>
                            </ul>
                        </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar" style="background-color: black" aria-label='Secondary'>
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <?= $this->render('_sidebar') ?>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Right side column. Contains the navbar and content of the page -->
    <main class="content-wrapper" id="main-content-div">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <small style="color:#000 !important;font-weight: 500"><?= $this->title ?></small>
            </h1>
            <ol class="breadcrumb" style="color:#000 !important;font-weight: 500" aria-label='breadcrumb'>
                <li><a href="<?= Yii::getAlias('@web') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li aria-current='location' class="active">Dashboard</li>
            </ol>
        </section>

        <!-- Main content -->

        <section class="content">
            <?= Alert::widget() ?>
            <?= $content ?>
        </section>
        <!-- /.content -->
    </main>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        &copy; <strong><a href="https://icta.go.ke" style='color:#B2210E'>ICT Authority, Kenya.</a></strong>
        
        <a href="https://icta.go.ke" style="float:right; color:#B2210E">ICT Authority, Kenya Sitemap</a>
    </footer>
</div>
<!-- ./wrapper -->
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
<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
