<?php

use yii\db\Migration;

class m180427_061325_auditlog extends Migration
{
    protected $table_name = '{{%staudit_log}}';

    /**
     * @see @yii/log/migrations
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->table_name, [
            'id' => $this->bigPrimaryKey(),
            'level' => $this->integer(),
            'category' => $this->string(),
            'log_time' => $this->double(),
            'prefix' => $this->text(),
            'message' => $this->text(),
        ], $tableOptions);

        $this->createIndex('staudit_log_idx_log_level', $this->table_name, 'level');
        $this->createIndex('staudit_log_idx_log_category', $this->table_name, 'category');
    }

    public function safeDown()
    {
        $this->dropTable($this->table_name);
    }
}
