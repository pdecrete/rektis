<?php

use yii\db\Migration;

class m180619_073531_status_per_board extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        Yii::$app->db->createCommand("SET SQL_MODE='ALLOW_INVALID_DATES'")->execute();

        $this->dropColumn('{{%stteacher}}', 'status');

        $this->addColumn('{{%stteacher_board}}', 'status', $this->smallInteger()->unsigned()->notNull()->defaultValue(0)->comment('Service status per board, in compliance for , i.e. 1 for appointed'));
        $this->createIndex('idx_teacher_board_status', '{{%stteacher_board}}', 'status', false);
    }

    public function safeDown()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        Yii::$app->db->createCommand("SET SQL_MODE='ALLOW_INVALID_DATES'")->execute();

        $this->dropColumn('{{%stteacher_board}}', 'status');

        $this->addColumn('{{%stteacher}}', 'status', $this->smallInteger()->unsigned()->notNull()->defaultValue(0)->comment('Service status, i.e. 1 for appointed'));
    }

}
