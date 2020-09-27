<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$db2 = require __DIR__ . '/db2.php';
$email_settings = require __DIR__ . '/email_settings.php';

$config = [
    'id' => 'accreditation',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'defaultRoute' => 'site/index',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'hW0B2aR1b30ytITFDd4e_zkvDTEJP2xt',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        /*'session' => [
			'timeout' => 60,
		],*/
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
            'loginUrl'=>['site/login'],            
            'authTimeout'=>900
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => $email_settings,
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'db2' => $db2,
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,            
                'rules' => [
                '' => 'site/index',
                '<action>'=>'site/<action>',
            ],
        ],
    ],
    'modules' => [
        'gridview' =>  [
            'class' => '\kartik\grid\Module',
            //'bsVersion' => 4,
            
        ],
        
        'professional' => [
            'class' => 'app\modules\professional\Professional',
        ],
    ],    
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
