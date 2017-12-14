<?php

use yii\db\Migration;

class m171113_104629_announcements extends Migration
{

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%announcement}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(500)->notNull(),
            'body' => $this->text()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultValue(0),
            'updated_at' => $this->timestamp()->notNull()->defaultValue(new yii\db\Expression('NOW()')),
            'deleted' => $this->boolean()->notNull()->defaultValue(false)
            ], $tableOptions);
        $this->createIndex('idx_by_deleted', '{{%announcement}}', ['deleted']);
        $this->createIndex('idx_by_date', '{{%announcement}}', ['updated_at']);
        
//        $this->alterColumn('{{%announcement}}', '[[created_at]]', $this->timestamp()->notNull());
    }

    public function safeDown()
    {
        $this->dropTable('{{%announcement}}');
    }
}
