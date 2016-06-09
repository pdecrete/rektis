<?php

return [
//    'interactive' => false,
    'sourcePath' => '@app',
    'messagePath' => '@app/messages',
    'languages' => [
        'el-GR'
    ],
    'translator' => 'Yii::t',
    'sort' => true,
    'removeUnused' => false,
    'markUnused' => true,
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
        '/vendor',
    ],
    'format' => 'php',
    'overwrite' => true,
    // Message categories to ignore
    'ignoreCategories' => [
        'yii',
    ],
        /*
          // 'db' output format is for saving messages to database.
          'format' => 'db',
          // Connection component to use. Optional.
          'db' => 'db',
          // Custom source message table. Optional.
          // 'sourceMessageTable' => '{{%source_message}}',
          // Custom name for translation message table. Optional.
          // 'messageTable' => '{{%message}}',
         */

        /*
          // 'po' output format is for saving messages to gettext po files.
          'format' => 'po',
          // Root directory containing message translations.
          'messagePath' => __DIR__ . DIRECTORY_SEPARATOR . 'messages',
          // Name of the file that will be used for translations.
          'catalog' => 'messages',
          // boolean, whether the message file should be overwritten with the merged messages
          'overwrite' => true,
         */
];
