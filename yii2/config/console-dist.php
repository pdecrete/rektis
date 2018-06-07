<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');
$aliases = require(__DIR__ . '/aliases.php');
$authmanager = require(__DIR__ . '/authmanager.php');

return [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'gii',
        // uncomment SubstituteTeacher to access dev env commands
        // 'SubstituteTeacher'
    ],
    'controllerNamespace' => 'app\commands',
    'modules' => [
        'gii' => 'yii\gii\Module',
        // uncomment module SubstituteTeacher to access dev env commands
        // 'SubstituteTeacher' => [ 'class' => 'app\modules\SubstituteTeacher\SubstituteTeacherModule' ],
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                // for future use. Declared here to create migration from console
                // create log table with: yii migrate --migrationPath=@yii/log/migrations/ @ console
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
    ],
    'params' => $params,
    'aliases' => $aliases,
];
