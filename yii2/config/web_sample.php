<?php

use kartik\datecontrol\Module;

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');
$aliases = require(__DIR__ . '/aliases.php');
$authmanager = require(__DIR__ . '/authmanager.php');
$messages = require(__DIR__ . '/messages.php');

$config = [
    'id' => 'adm',
    'name' => 'Εφαρμογή υποστήριξης διοικητικού έργου',
    'language' => 'el-GR',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'admpd1617shuyb2b4390xds83b34hf8dhjj',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'on '.\yii\web\User::EVENT_AFTER_LOGIN => ['app\models\LoginForm', 'loginLog'],
            'on '.\yii\web\User::EVENT_BEFORE_LOGOUT => ['app\models\LoginForm', 'loginLog']
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
				'class' => 'Swift_SmtpTransport',
				'host' => 'SMTP_HOST',  
				'username' => 'USER',
				'password' => 'PASSWORD',
				'port' => '587', 
				'encryption' => 'tls',
			],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'logFile' => '@runtime/logs/all.log',
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'categories' => ['login'],
                    'logFile' => '@runtime/logs/login.log',
                    'logVars' => [],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'categories' => ['leave-email', 'contact-email', 'transport-journal-email'],
                    'logFile' => '@runtime/logs/email.log',
                    'logVars' => [],
                ],                
                // for now, log employee changes to employee.log file
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'categories' => ['employee'],
                    'logFile' => '@runtime/logs/employee.log',
                    'logVars' => []
                ],
                // future use (or not?): log to db
                /*
                [
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['info'],
                    'categories' => ['employee'],
                    'logTable' => 'employee_log',
                    'logVars' => [],
                    'db' => $db
                ]
                */
				
            ],
        ],
        'db' => $db,
        'authManager' => $authmanager,
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'dd/MM/yyyy',
            'datetimeFormat' => 'dd/MM/yyyy, hh:mm',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'EUR',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => true,
            'enableStrictParsing' => false,
            'rules' => [
                'employees' => 'employee/index',
                'employee/<id:\d+>' => 'employee/view'
            ],
        ],
        'i18n' => $messages,
    ],
    'params' => $params,
    'aliases' => $aliases,
    // 'catchAll' => ['site/offline'],
    'modules' => [
        'datecontrol' => [
            'class' => 'kartik\datecontrol\Module',
            // format settings for displaying each date attribute (ICU format example)
            'displaySettings' => [
                Module::FORMAT_DATE => 'dd/MM/yyyy',
                Module::FORMAT_TIME => 'hh:mm:ss a',
                Module::FORMAT_DATETIME => 'dd/MM/yyyy hh:mm:ss a',
            ],
            // format settings for saving each date attribute (PHP format example)
            'saveSettings' => [
                Module::FORMAT_DATE => 'php:Y-m-d', // saves as unix timestamp
                Module::FORMAT_TIME => 'php:H:i:s',
                Module::FORMAT_DATETIME => 'php:Y-m-d H:i:s',
            ],
            // set your display timezone
            'displayTimezone' => 'Europe/Athens',
            // set your timezone for date saved to db
            //'saveTimezone' => 'UTC+2',
            // automatically use kartik\widgets for each of the above formats
            'autoWidget' => true,
            // default settings for each widget from kartik\widgets used when autoWidget is true
            'autoWidgetSettings' => [
                Module::FORMAT_DATE => ['type' => 2, 'pluginOptions' => ['autoclose' => true]], // example
                Module::FORMAT_DATETIME => [], // setup if needed
                Module::FORMAT_TIME => [], // setup if needed
            ],
        ]
    ]
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['*'],
    ];
}

return $config;
