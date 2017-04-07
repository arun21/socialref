<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
        'company' => [
            'class' => 'app\modules\company\Module',
        ],
        'social' => [
            // the module class
            'class' => 'kartik\social\Module',

            // the global settings for the facebook plugins widget
            'facebook' => [
                'appId' => '357086327707450',
                'secret' => 'c8fe337815ffe63b52e987b40ade2783',
            ],

            // the global settings for the twitter plugins widget
            'twitter' => [
                'screenName' => 'TWITTER_SCREEN_NAME'
            ],
        ],
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'dlfjndffjgnsdkjfs',
        ],
        'view' => [
            'theme' => [
                'basePath' => '@app/themes/socialref',
                'baseUrl' => '@web/themes/socialref',
                'pathMap' => [
                    '@app/views' => '@app/themes/socialref',
                ],
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\Employee',
            'enableAutoLogin' => true,
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'linkedin' => [
                    'class' => 'yii\authclient\clients\LinkedIn',
                    'clientId' => '86xycv37cod4nt',
                    'clientSecret' => 'AWSqF4H20cAjmxdv',
                    'attributeNames' => [
                        'id',
                        'email-address',
                        'first-name',
                        'last-name',
                        'public-profile-url',
                        'headline',
                        'summary',
                        'specialties',
                        'picture-url'
                    ]
                ],
                'twitter' => [
                    'class' => 'yii\authclient\clients\Twitter',
                    'attributeParams' => [
                        'include_email' => 'true'
                    ],
                        'consumerKey' => 'MIQuuelPnZ5eBuQriE8fgyPtr',
                        'consumerSecret' => 'zkhgb6jW08kEtOHSzUqo2aIT5dx2Z36Beh5PE1ASoJztsrsYc9',
                ],
            ],
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
//            'transport' => [
//                'class' => 'Swift_SmtpTransport',
//                'host' => 'localhost',
//                'username' => 'root',
//                'password' => '',
//                'port' => '587',
//                'encryption' => 'tls',
//            ],
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
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'i18n' => [
            'translations' => [
                    'app' => [
                        'class' => 'yii\i18n\PhpMessageSource',
                        'basePath' => '@app/messages',
                    ],
                    'kvsocial' => [
                        'class' => 'yii\i18n\PhpMessageSource',
                        'basePath' => '@vendor/kartik-v/yii2-social/messages',
                    ],
            ],
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
