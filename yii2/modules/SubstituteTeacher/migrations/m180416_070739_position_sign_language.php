<?php

use yii\db\Migration;

class m180416_070739_position_sign_language extends Migration
{
    public function safeUp()
    {
        Yii::$app->db->createCommand("SET SQL_MODE='ALLOW_INVALID_DATES'")->execute();

        // add mark for special priority requirement for sign language
        $this->addColumn('{{%stposition}}', 'sign_language', $this->boolean()->notNull()->defaultValue(false));
    }

    public function safeDown()
    {
        Yii::$app->db->createCommand("SET SQL_MODE='ALLOW_INVALID_DATES'")->execute();

        $this->dropColumn('{{%stposition}}', 'sign_language');
    }
}
