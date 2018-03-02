<?php

use yii\db\Migration;

class m171115_110515_files extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        Yii::$app->db->createCommand("SET SQL_MODE='ALLOW_INVALID_DATES'")->execute();

        $this->createTable('{{%stfile}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(500)->notNull(),
            'original_filename' => $this->string(500)->notNull(),
            'mime' => $this->string(90)->notNull(),
            'size' => $this->integer()->unsigned()->notNull(),
            'filename' => $this->string(500)->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultValue(0),
            'updated_at' => $this->timestamp()->notNull()->defaultValue(new yii\db\Expression('NOW()')),
            'deleted' => $this->boolean()->notNull()->defaultValue(false)
            ], $tableOptions);
        $this->createIndex('idx_stfile_by_deleted', '{{%stfile}}', ['deleted']);
        $this->createIndex('idx_stfile_by_date', '{{%stfile}}', ['updated_at']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%stfile}}');
    }
}
