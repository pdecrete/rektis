<?php

use yii\db\Migration;

/**
 * Create supportive tables to keep track of applications submitted
 * via the rektis-application-frontend web app.
 * The tables hold applicant status information (submitted, denied, rejected...)
 * and the positions requested by the applicant.
 */
class m180508_092445_applications extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        Yii::$app->db->createCommand("SET SQL_MODE='ALLOW_INVALID_DATES'")->execute();

        // holds the application information
        $this->createTable('{{%stapplication}}', [
            'id' => $this->primaryKey(),
            'call_id' => $this->integer(),
            'teacher_board_id' => $this->integer(),
            'agreed_terms_ts' => $this->integer()->defaultValue(null), // value from frontend
            'state' => $this->integer()->defaultValue(0), // value from frontend // 0 is OK, 1 is denied
            'state_ts' => $this->integer()->defaultValue(null), // value from frontend
            'created_at' => $this->timestamp()->notNull()->defaultValue(0),
            'updated_at' => $this->timestamp()->notNull()->defaultValue(new yii\db\Expression('NOW()')),
            'deleted' => $this->boolean()->notNull()->defaultValue(false)
        ], $tableOptions);
        $this->addForeignKey('fk_call_id', '{{%stapplication}}', 'call_id', '{{%stcall}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_teacher_board_id', '{{%stapplication}}', 'teacher_board_id', '{{%stteacher_board}}', 'id', 'SET NULL', 'CASCADE');

        $this->createTable('{{%stapplication_positions}}', [
            'id' => $this->primaryKey(),
            'application_id' => $this->integer(),
            'call_position_id' => $this->integer(),
            'order' => $this->smallInteger()->notNull(), // value from frontend
            'updated' => $this->integer()->notNull(), // value from frontend
            'deleted' => $this->boolean()->notNull() // value from frontend
        ], $tableOptions);
        $this->addForeignKey('fk_application_id', '{{%stapplication_positions}}', 'application_id', '{{%stapplication}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_call_position_id', '{{%stapplication_positions}}', 'call_position_id', '{{%stcall_position}}', 'id', 'SET NULL', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable('{{%stapplication_positions}}');
        $this->dropTable('{{%stapplication}}');
    }
}
