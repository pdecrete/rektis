<?php
return [
    'sourcePath' => '@app/modules/SubstituteTeacher',
    'messagePath' => '@app/modules/SubstituteTeacher/messages',
    'languages' => [
        'el-GR'
    ],
    'translator' => 'Yii::t',
    'sort' => true,
    'removeUnused' => true,
    'only' => [
        '*.php'
    ],
    'except' => [
        '.svn',
        '.git',
        '.gitignore',
        '.gitkeep',
        '.hgignore',
        '.hgkeep',
        '/messages',
    ],
    'format' => 'php',
    'overwrite' => true,
    'ignoreCategories' => [
        'yii',
    ],
];
