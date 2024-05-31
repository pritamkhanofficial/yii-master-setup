<?php

$params = require __DIR__ . '/params.php';
$routes = require __DIR__ . '/routes.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],

    'modules' => [
        'gridview' =>  [
        'class' => '\kartik\grid\Module'
            // enter optional module parameters below - only if you need to  
            // use your own export download action or custom translation 
            // message source
            // 'downloadAction' => 'gridview/export/download',
            // 'i18n' => []
        ],        
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'zldefjiyiOoVHzX0kr-f2l261cy44RrN',
            'enableCsrfValidation' => false,
            // 'baseUrl' => '/web',
            //'assetsUrl' => '@web/back/'
        ],
        'assetManager' => [
            'basePath' => '@webroot/assets',
            // 'linkAssets' => false,
            /* 'basePath' => '@webroot/assets',*/
            'baseUrl' => '@web/assets/', 
            'bundles' => [
                'yii\web\YiiAsset' => [
                    //'sourcePath' => null,
                    // 'linkAssets' => true ,
                    // 'depends' => ['yii\web\YiiAsset'],
                    'js'=>[
                        'yii.js',
                        // 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js'
                        ]
                ],
                    /* 'yii\web\JqueryAsset' => [
                        'js'=>[
                            'https://code.jquery.com/jquery-3.6.0.js'
                        ]
                    ], */
                /* 'yii\bootstrap5\BootstrapPluginAsset' => [
                    'js'=>[]
                ], */
                'yii\bootstrap4\BootstrapAsset' => [
                    'css' => [],
                ],
                'yii\bootstrap5\BootstrapAsset' => [
                    'css' => [],
                ],
                'kartik\form\ActiveFormAsset' => [
                    'bsDependencyEnabled' => true // do not load bootstrap assets for a specific asset bundle
                ],
            ],
        ],
        'dateFormat' => 'yy-MM-dd',
        'formatter' => [
            'defaultTimeZone' => 'UTC',
            'timeZone' => $params['defaultTimeZone'],
            'dateFormat' => 'php:Y-m-d',
            'datetimeFormat'=>'php:Y-m-d H:i:s'
        ],
        'helpers' => [
            'class' => 'app\components\Helpers',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'class'=>'yii\web\User',
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl'=>['site/index']
        ],
        'accesspermission' => [

        'class' => 'app\models\Accesspermission',

        ],
        'errorHandler' => [
            
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
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
        
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            //'suffix' => '.php',
            'rules' => $routes,
            
        ],
    ],
    'as beforeRequest' => [  //if guest user access site so, redirect to login page.
        'class' => 'yii\filters\AccessControl',
        'except' => ['site/index','site/forgotpassword'],
        'rules' => [
            [
                'actions' => ['login', 'error'],
                'allow' => true,
            ],
            [
                'allow' => true,
                'roles' => ['@'],
            ],
        ],
    ],
    'params' => $params,
    'defaultRoute' => 'site/index',
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
        //'allowedIPs' => ['*'],
        // 'allowedIPs' => ['127.0.0.1', '::1']
    ];
}

return $config;
