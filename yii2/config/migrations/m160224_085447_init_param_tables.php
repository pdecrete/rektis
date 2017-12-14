<?php

use yii\helpers\Console;
use yii\db\Migration;

class m160224_085447_init_param_tables extends Migration
{

    protected $tables = [
        ['specialisation', "
                `id` integer not null auto_increment,
                `code` varchar(10) not null comment 'κωδικός ειδικότητας',
                `name` varchar(100) not null comment 'λεκτικό',
                primary key (`id`),
                unique index specialisation_code (`code`)
            "],
        ['service', "
                `id` integer not null auto_increment,
                `name` varchar(100) not null,
                `information` varchar(500) not null,
                primary key (`id`),
                unique index service_name (`name`)
            "],
        ['position', "
                `id` integer not null auto_increment,
                `name` varchar(100) not null,
                `comments` text not null,
                primary key (`id`),
                unique index position_name (`name`)
            "],
        ['employee_status', "
                `id` integer not null auto_increment,
                `name` varchar(100) not null,
                primary key (`id`),
                unique index status_name (`name`)
            "],
    ];

    public function safeUp()
    {
        $tables_cnt = count($this->tables);
        for ($i = 0; $i < $tables_cnt; $i++) {
            $table_realname = $this->tables[$i][0];
            $columns = $this->tables[$i][1];

            $table_name = $this->db->tablePrefix . $table_realname;

            Yii::$app->db->createCommand("create table if not exists `{$table_name}` ({$columns}) engine=InnoDB charset=utf8;")
                    ->execute();

            $table_schema = Yii::$app->db->schema->getTableSchema($table_name);
            if ($table_schema === null) {
                Console::stdout("Table '{$table_name}' does not appear to exist; considering create unsuccessful.\n", Console::FG_RED);
            } else {
                Console::stdout("Created table '{$table_name}'.\n", Console::FG_GREEN);
            }
        }
    }

    public function safeDown()
    {
        $tables_cnt = count($this->tables);
        for ($i = $tables_cnt - 1; $i >= 0; $i--) {
            $table_realname = $this->tables[$i][0];
            $columns = $this->tables[$i][1];

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

}
