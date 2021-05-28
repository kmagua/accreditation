<?php

/**
 * @var string $content
 * @var \yii\web\View $this
 */

use yii\helpers\Html;

$bundle = yiister\gentelella\assets\Asset::register($this);

?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta charset="<?= Yii::$app->charset ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?= Html::csrfMetaTags() ?>
    <?php $this->registerCSS("
        .nav_menu{
            background:#009a3d !important;
            color:#fff !important;
        }
        .left_col{
            background:#dc281e !important;        
        }
        body{
            color:#000 !important;
        }
        .main_menu_side  {
            background:#dc281e !important;  
        }
        .top_nav{
         background:#dc281e !important;
        }
        .nav_title{
            background:black !important;
        }
        .nav_menu a{
            color:#fff !important;
            background:#009a3d !important;
        }
        .nav_menu a:hover{
            color:#fff !important;
            background: black !important;
        }
        .nav_menu form{
            color:#fff !important;
            background:#009a3d;!
        }
        
        .nav.side-menu > li.active > a {    
            background: black !important;    
        }
        
        .sidebar-footer a{
            background:black !important;
        }
        
        "); ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="nav-<?= !empty($_COOKIE['menuIsCollapsed']) && $_COOKIE['menuIsCollapsed'] == 'true' ? 'sm' : 'md' ?>" >
<?php $this->beginBody(); ?>
<div class="container body">

    <div class="main_container">

        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">
                

                <div class="navbar nav_title" style="border: 0; background-size: contain;" >
                    <img src="<?= Yii::getAlias('@web/images/icta_logo_new_1.png'); ?>" style="height:100%">
                </div>
                <div class="clearfix"></div>

                <!-- menu prile quick info -->
                <div class="profile">
                    <div class="profile_pic">
                        <img src="<?= Yii::getAlias('@web/images/profile_pic.png') ?>" alt="..." class="img-circle profile_img">
                    </div>
                    <div class="profile_info">
                        <span>Welcome,</span>
                        <h2><?= (Yii::$app->user->isGuest)?'Guest': Yii::$app->user->identity->full_name ?> </h2>
                    </div>
                </div>
                <!-- /menu prile quick info -->

                <br />

                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

                    <div class="menu_section">
                        <h3>Accreditation System</h3>
                        <?=
                        \yiister\gentelella\widgets\Menu::widget(
                            [
                                'items' => [
            ['label' => 'Home', 'url' => ['/site/index'] ,"icon" => "home"],
            /*['label' => 'Applications', 'url' => ['/application/index'],
                'visible'=> (!Yii::$app->user->isGuest && Yii::$app->user->identity->isInternal()),
                'linkOptions' => ['style' => 'color: white;font-weight: 900;margin-top: 25px;']],*/
            
            [
                /*'label' => 'Applications', 'linkOptions' => ['style' => 'color: white;font-weight: 450;margin-top: 20px;'],
                'items' => [
                    ['label' => 'Company Accreditation', 'url' => ['/application/index'],
                        //'visible'=> (!Yii::$app->user->isGuest && Yii::$app->user->identity->isInternal()),
                        'linkOptions' => ['style' => 'color: white;font-weight: 450; background-color:green']],
                    ['label' => 'My Pending Reviews', 'url' => ['/application/my-assigned'],
                        //'visible'=> (!Yii::$app->user->isGuest && Yii::$app->user->identity->isInternal()),
                        'linkOptions' => ['style' => 'color: white;font-weight: 450;background-color:green']],
                    
                ],*/
                
                "label" => "Applications",
                "icon" => "th",
                "url" => "#",
                "items" => [
                    ["label" => "Company Accreditation", "url" => ['/application/index']],
                    ["label" => "My Pending Reviews", "url" => ['/application/my-assigned']],
                ],
                
                'visible'=> (!Yii::$app->user->isGuest && Yii::$app->user->identity->isInternal())
            ],
            ['label' => 'PDTP Applications', 'url' => ['/application/pdpt-applications'], 
                'linkOptions' => ['style' => 'color: white;font-weight: 450;margin-top: 20px;'], 'visible'=> (!Yii::$app->user->isGuest && Yii::$app->user->identity->inGroup('pdtp', false))],
            [
                'label' => 'Quick Links', 'url' => '#', "icon" => "th",
                'items' => [
                    ['label' => 'Company Profile', 'url' => ['/company-profile/create'],],
                    //['label' => 'Professional', 'url' => ['/professional/personal-information/my-profile'],
                    //    'linkOptions' => ['style' => 'color: white;font-weight: 450;background-color:green']],
                    ['label' => 'My Account', 'url' => ['/user/my-profile']],
                    ['label' => 'FAQs', 'url' => ['/site/faqs']],
                    ['label' => 'Change My Password', 'url' => ['/user/change-password']],
                ],
                'visible'=> (!Yii::$app->user->isGuest && Yii::$app->user->identity->inGroup('Applicant', false))
            ],
            [
                'label' => 'Administration', 'url' => '#', 'icon' => 'user',
                'items' => [
                    ['label' => 'Users', 'url' => ['/user/index']],
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
            ['label' => 'Login', 'url' => ['/site/login'],'linkOptions' => ['style' => 'color: white;font-weight: 450;margin-top: 20px;'], 'visible'=>Yii::$app->user->isGuest]
            
        ]
                                
                            ]
                        )
                        ?>
                    </div>

                </div>
                <!-- /sidebar menu -->

                <!-- /menu footer buttons -->
                <div class="sidebar-footer hidden-small">
                    <a data-toggle="tooltip" data-placement="top" title="Settings">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                        <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Lock">
                        <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Logout">
                        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                    </a>
                </div>
                <!-- /menu footer buttons -->
            </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">

            <div class="nav_menu">
                <nav class="" role="navigation">
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="">
                            <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <img src="<?= Yii::getAlias('@web/images/profile_pic.png') ?>" alt="">
                                    <?php if(Yii::$app->user->isGuest){ echo "Welcome Guest"; }else{ echo Yii::$app->user->identity->full_name; } ?>
                                <span class=" fa fa-angle-down"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu pull-right">
                                <?php if(!Yii::$app->user->isGuest){ ?><li>
                                    <a href="<?= Yii::getAlias('@web/user/my-profile') ?>"> Profile</a>
                                </li>
                                
                                <li><?php echo Html::beginForm(['/site/logout'], 'post');
                echo Html::submitButton(
                    'Log Out',
                    ['class' => '', 'style'=>"background-color:#009a3d"]
                );
                echo Html::endForm(); ?>
                                </li>
                                <?php }else{ ?>
                                <li> <a href="<?= Yii::getAlias('@web/site/login') ?>">Login</a> </li>
                                <?php } ?>
                            </ul>
                        </li>

                        <li role="presentation" class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-envelope-o"></i>
                                <span class="badge bg-green">6</span>
                            </a>
                            <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                                <li>
                                    <a>
                      <span class="image">
                                        <img src="http://placehold.it/128x128" alt="Profile Image" />
                                    </span>
                      <span>
                                        <span>John Smith</span>
                      <span class="time">3 mins ago</span>
                      </span>
                      <span class="message">
                                        Film festivals used to be do-or-die moments for movie makers. They were where...
                                    </span>
                                    </a>
                                </li>
                                <li>
                                    <a>
                      <span class="image">
                                        <img src="http://placehold.it/128x128" alt="Profile Image" />
                                    </span>
                      <span>
                                        <span>John Smith</span>
                      <span class="time">3 mins ago</span>
                      </span>
                      <span class="message">
                                        Film festivals used to be do-or-die moments for movie makers. They were where...
                                    </span>
                                    </a>
                                </li>
                                <li>
                                    <a>
                      <span class="image">
                                        <img src="http://placehold.it/128x128" alt="Profile Image" />
                                    </span>
                      <span>
                                        <span>John Smith</span>
                      <span class="time">3 mins ago</span>
                      </span>
                      <span class="message">
                                        Film festivals used to be do-or-die moments for movie makers. They were where...
                                    </span>
                                    </a>
                                </li>
                                <li>
                                    <a>
                      <span class="image">
                                        <img src="http://placehold.it/128x128" alt="Profile Image" />
                                    </span>
                      <span>
                                        <span>John Smith</span>
                      <span class="time">3 mins ago</span>
                      </span>
                      <span class="message">
                                        Film festivals used to be do-or-die moments for movie makers. They were where...
                                    </span>
                                    </a>
                                </li>
                                <li>
                                    <div class="text-center">
                                        <a href="/">
                                            <strong>See All Alerts</strong>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </nav>
            </div>

        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
            <?php if (isset($this->params['h1'])): ?>
                <div class="page-title">
                    <div class="title_left">
                        <h1><?= $this->params['h1'] ?></h1>
                    </div>
                    <div class="title_right">
                        <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search for...">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button">Go!</button>
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="clearfix"></div>
            <?= yii\widgets\Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
            <?= $content ?>
        </div>
        <!-- /page content -->
        <!-- footer content -->
        <footer>
            <div class="pull-left">
                <!--Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com" rel="nofollow" target="_blank">Colorlib</a><br />
                Extension for Yii framework 2 by <a href="http://yiister.ru" rel="nofollow" target="_blank">Yiister</a> -->
                <p class="pull-left">&copy; ICT Authority <?= date('Y') ?></p>
            </div>
            <div class="clearfix"></div>
            
        </footer>
        <!-- /footer content -->
    </div>

</div>

<div id="custom_notifications" class="custom-notifications dsp_none">
    <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
    </ul>
    <div class="clearfix"></div>
    <div id="notif-group" class="tabbed_notifications"></div>
</div>
<!-- /footer content -->
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
<?php $this->endPage(); ?>
