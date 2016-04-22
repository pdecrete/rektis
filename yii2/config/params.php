<?php
use kartik\datecontrol\Module;

return [
    'companyName' => 'ΠΔΕ Κρήτης',
    'adminEmail' => 'spapad@gmail.com',
    'user.passwordResetTokenExpire' => 3600,
    'user.rememberMeDuration' => 172800, // seconds of cookie duration for auto login (if enabled)
    // format settings for displaying each date attribute (ICU format example)
    'dateControlDisplay' => [
        Module::FORMAT_DATE => 'dd/MM/yyyy',
        Module::FORMAT_TIME => 'hh:mm:ss a',
        Module::FORMAT_DATETIME => 'dd/MM/yyyy hh:mm:ss a',
    ],
    // format settings for saving each date attribute (PHP format example)
    'dateControlSave' => [
        Module::FORMAT_DATE => 'php:U',
        Module::FORMAT_TIME => 'php:H:i:s',
        Module::FORMAT_DATETIME => 'php:Y-m-d H:i:s',
    ]
];
