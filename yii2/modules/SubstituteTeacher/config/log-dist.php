<?php

return [
    'class' => 'yii\log\DbTarget',
    'logTable' => '{{%staudit_log}}',
    // 'levels' => ['error', 'warning'],
    'categories' => [
        'app\modules\SubstituteTeacher*',
    ],
    'logVars' => ['_GET', '_POST'],
];
