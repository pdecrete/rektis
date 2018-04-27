<?php

use yii\db\Migration;

class m180427_081335_pages extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%page}}', [
            'id' => $this->primaryKey(),
            'identity' => $this->string()->notNull(),
            'title' => $this->string()->notNull(),
            'content' => $this->text(),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null),
        ], $tableOptions);

        $this->createIndex('idx-page-identity', '{{%page}}', 'identity', true);
    }

    public function safeDown()
    {
        $this->dropTable('{{%page}}');
    }
}
