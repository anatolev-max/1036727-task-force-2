<?php

$params = array_merge(
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@avatars' => 'uploads/avatars',
        '@files' => 'uploads/files',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\redis\Cache',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => $params['redis_hostname'],
            'port' => $params['redis_port'],
            'database' => $params['redis_database'],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'vkontakte' => [
                    'class' => 'yii\authclient\clients\VKontakte',
                    'clientId' => $params['vkontakte_client_id'],
                    'clientSecret' => $params['vkontakte_client_secret'],
                    'scope' => 'email',
                ],
            ],
        ],
        'geocoder' => [
            'class' => 'app\components\GeocoderApiClient'
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'M48085L3F3Oy7cV4U01mWd1We7OtSiiG',
        ],
        'user' => [
            'identityClass' => 'app\models\UserIdentity',
            'enableAutoLogin' => false,
            'loginUrl' => ['landing/index'],
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
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                '/' => 'landing/index',
                'api/coords/<geocode>' => 'geo/coords',
                'api/cities/<query>' => 'geo/cities',
                'login' => 'user/login',
                'logout' => 'user/logout',
                'my-tasks' => 'tasks/user-tasks',
                'reply/accept/<replyId:\d+>' => 'reply/accept',
                'reply/create' => 'reply/create',
                'reply/refuse/<replyId:\d+>' => 'reply/refuse',
                'signup' => 'user/signup',
                'tasks' => 'tasks/index',
                'tasks/add' => 'tasks/create',
                'tasks/<category:(?!view)>' => 'tasks/index',
                'tasks/cancel/<taskId:\d+>' => 'tasks/cancel',
                'tasks/complete' => 'tasks/complete',
                'tasks/refuse/<taskId:\d+>' => 'tasks/refuse',
                'tasks/view/<taskId:\d+>' => 'tasks/view',
                'user/auth' => 'user/auth',
                'settings' => 'profile/settings',
                'user/view/<userId:\d+>' => 'profile/view',
            ],
        ],
    ],
    'params' => $params,
    'language' => 'ru',
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
