<?php

?>

<!-- Sidebar user panel -->
<div class="user-panel">
    <div class="pull-left image">
        <?php echo \cebe\gravatar\Gravatar::widget(
            [
                'email' => 'username@example.com',
                'options' => [
                    'alt' => 'username',
                ],
                'size' => 64,
            ]
        ); ?>
    </div>
    <div class="pull-left info">
        <p>username</p>

        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
    </div>
</div>


<!-- search form -->
<form action="#" method="get" class="sidebar-form">
    <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Search..."/>
        <span class="input-group-btn">
            <button type='submit' name='seach' id='search-btn' class="btn btn-flat"><i
                        class="fa fa-search"></i></button>
        </span>
    </div>
</form>
<!-- /.search form -->


<?php

// prepare menu items, get all modules
$menuItems = [];

$favouriteMenuItems[] = ['label' => 'MAIN NAVIGATION', 'options' => ['class' => 'header']];


$applicationsMenuItems = [];
$applicationsMenuItems[] = [
    'url' => ['/application/index'],
    'icon' => 'cog',
    'label' => 'Company Accreditation',
];
$applicationsMenuItems[] = [
    'icon' => 'cog',
    'label' => 'My Pending Reviews',
    'url' => ['/application/my-assigned'],
];

$quickLinksMenuItems = [];
$quickLinksMenuItems[] = [
    'url' => ['/company-profile/create'],
    'icon' => 'cog',
    'label' => 'Company Profile',
];
$quickLinksMenuItems[] = [
    'icon' => 'user',
    'label' => 'My Account',
    'url' => ['/user/my-profile'],
];
$quickLinksMenuItems[] = [
    'icon' => 'user',
    'label' => 'FAQs',
    'url' => ['/site/faqs'],
];
$quickLinksMenuItems[] = [
    'icon' => 'lock',
    'label' => 'Change My Password',
    'url' => ['/user/change-password'],
];


$adminMenuItems = []; // to start here
$adminMenuItems[] = [
    'url' => ['/company-profile/create'],
    'icon' => 'cog',
    'label' => 'Company Profile',
];
$adminMenuItems[] = [
    'icon' => 'user',
    'label' => 'My Account',
    'url' => ['/user/my-profile'],
];
$adminMenuItems[] = [
    'icon' => 'user',
    'label' => 'FAQs',
    'url' => ['/site/faqs'],
];
$adminMenuItems[] = [
    'icon' => 'lock',
    'label' => 'Change My Password',
    'url' => ['/user/change-password'],
];

$menuItems[] = [
    'url' => ['/site/login'],
    'icon' => 'unlock',
    'label' => 'Login',
    'visible' => Yii::$app->user->isGuest ,
];
$menuItems[] = [
    'url' => ['/user/register'],
    'icon' => 'user-plus',
    'label' => 'Register',
    'visible' => Yii::$app->user->isGuest ,
];

$menuItems[] = [
    'url' => ['/application/pdpt-applications'],
    'icon' => 'cog',
    'label' => 'PDTP Applications',
    'visible'=> (!Yii::$app->user->isGuest && Yii::$app->user->identity->inGroup('pdtp', false))
];

$menuItems[] = [
    #'url' => '#',
    'icon' => 'list',
    'label' => 'Applications',
    'items' => $applicationsMenuItems,
    'visible'=> (!Yii::$app->user->isGuest && Yii::$app->user->identity->isInternal())
];

$menuItems[] = [
    #'url' => '#',
    'icon' => 'cog',
    'label' => 'Quick Links',
    'items' => $quickLinksMenuItems,
    'visible'=> (!Yii::$app->user->isGuest && Yii::$app->user->identity->inGroup('Applicant', false))
];

/*for ($i = 0; $i < 25; $i++) {
    $menuItems[] = [
        'url' => ['/test/auto', 'id' => $i],
        'icon' => 'cog',
        'label' => 'Auto '.$i,
    ];
}*/

echo dmstr\widgets\Menu::widget([
    'items' => \yii\helpers\ArrayHelper::merge($favouriteMenuItems, $menuItems),
]);