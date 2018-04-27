<?php

use yii\db\Migration;

class m180312_115746_teacher_board_order extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        Yii::$app->db->createCommand("SET SQL_MODE='ALLOW_INVALID_DATES'")->execute();

        // this should be placed in a "per board" table
        $this->dropColumn('{{%stteacher}}', 'points');

        $this->createTable('{{%stteacher_board}}', [
            'id' => $this->primaryKey(),
            'teacher_id' => $this->integer(),
            'specialisation_id' => $this->integer(),
            'board_type' => $this->smallInteger()->unsigned()->notNull()->defaultValue(0)->comment('0, one board, 1 primary board, 2 secondary board'),
            'points' => $this->decimal(9, 3)->unsigned()->notNull()->defaultValue(0.0),
            'order' => $this->integer()->unsigned()->notNull()->defaultValue(0),
        ]);
        $this->addForeignKey('fk_teacher_board_teacher_id', '{{%stteacher_board}}', 'teacher_id', '{{%stteacher}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_teacher_board_specialisation_id', '{{%stteacher_board}}', 'specialisation_id', '{{%specialisation}}', 'id', 'SET NULL', 'CASCADE');
        $this->createIndex('idx_teacher_board_order', '{{%stteacher_board}}', ['teacher_id', 'specialisation_id', 'board_type']);
        $this->createIndex('idx_teacher_board_unique', '{{%stteacher_board}}', ['teacher_id', 'specialisation_id'], true);
    }

    public function safeDown()
    {
        Yii::$app->db->createCommand("SET SQL_MODE='ALLOW_INVALID_DATES'")->execute();

        $this->dropTable('{{%stteacher_board}}');
    }
}
