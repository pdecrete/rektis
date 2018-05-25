<?php

// return a 2 dimensional array; add any targets

return [
    [
        'class' => 'yii\log\DbTarget',
        'logTable' => '{{%email_audit_log}}',
        'categories' => [
            'app\modules\Email*',
            'yii\swiftmailer\Logger::add'
        ],
        'logVars' => [],
    ]
];
