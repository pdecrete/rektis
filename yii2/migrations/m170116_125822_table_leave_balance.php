<?php

use yii\helpers\Console;
use yii\db\Migration;

class m170116_125822_table_leave_balance extends Migration
{
    protected $table = 
        ['leave_balance', " 
				`id` integer NOT NULL AUTO_INCREMENT,
				`employee` integer NULL COMMENT 'Εργαζόμενος',
				`leave_type` integer NULL COMMENT 'Τύπος Άδειας',
				`year` varchar(4) NULL COMMENT 'Έτος',
				`days` smallint NULL DEFAULT 0 COMMENT 'Ημέρες',
				PRIMARY KEY (`id`),
				KEY `employee_fk_index` (`employee`),
				KEY `leave_type_fk_index` (`leave_type`)			
			"];

    public function safeUp()
    {
            $table_realname = $this->table[0];
            $columns = $this->table[1];

            $table_name = $this->db->tablePrefix . $table_realname;

            Yii::$app->db->createCommand("create table if not exists `{$table_name}` ({$columns}) engine=InnoDB charset=utf8;")
                      ->execute();

            $table_schema = Yii::$app->db->schema->getTableSchema($table_name);
            if ($table_schema === null) {
                Console::stdout("Table '{$table_name}' does not appear to exist; considering create unsuccessful.\n", Console::FG_RED);
            } else {
                Console::stdout("Created table '{$table_name}'.\n", Console::FG_GREEN);
				$this->addForeignKey('employee_fk', $table_name, 'employee', 'admapp_employee', 'id', 'SET NULL', 'CASCADE');
				$this->addForeignKey('leave_type_fk', $table_name, 'leave_type', 'admapp_leave_type', 'id', 'SET NULL', 'CASCADE');
            }
    }

    public function safeDown()
    {
            $table_realname = $this->table[0];
            $columns = $this->table[1];

            $table_name = $this->db->tablePrefix . $table_realname;
            $table_schema = Yii::$app->db->schema->getTableSchema($table_name);
            if ($table_schema === null) {
                Console::stdout("Table '{$table_name}' does not appear to exist; considering drop successful.\n", Console::FG_YELLOW);
            } else {
                Console::stdout("Dropping table '{$table_name}'.\n");
                $this->dropTable($table_name);
            }
    }
}
