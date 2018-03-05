<?php

use yii\db\Migration;

class m180115_071412_operation_logo extends Migration
{
    public function safeUp()
    {
        Yii::$app->db->createCommand("SET SQL_MODE='ALLOW_INVALID_DATES'")->execute();
        $this->addColumn('{{%stoperation}}', 'logo', $this->string(500)->notNull()->defaultValue(''));
    }

    public function safeDown()
    {
        Yii::$app->db->createCommand("SET SQL_MODE='ALLOW_INVALID_DATES'")->execute();
        $this->dropColumn('{{%stoperation}}', 'logo');
    }
}
