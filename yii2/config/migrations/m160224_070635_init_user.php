<?php

use yii\helpers\Console;
use yii\db\Migration;

class m160224_070635_init_user extends Migration
{

    public function safeUp()
    {
        $table_name = $this->db->tablePrefix . 'user';

        Yii::$app->db->createCommand("
create table if not exists `{$table_name}` (
    `id` integer not null auto_increment,
    `username` varchar(128) not null,
    `auth_key` varchar(32) not null,
    `password_hash` varchar(200) not null,
    `password_reset_token` varchar(200),
    `email` varchar(128) not null,
    `name` varchar(128) not null,
    `surname` varchar(128) not null,
    `status` smallint not null default 1,
    `last_login` timestamp comment 'last sucessful login',
    `create_ts` timestamp not null,
    `update_ts` timestamp not null,
    primary key (`id`),
    unique index admapp_user_username (`username`),
    unique index admapp_user_password_token (`password_reset_token`)
) engine=InnoDB charset=utf8;
		")
                ->execute();

        $table_schema = Yii::$app->db->schema->getTableSchema($table_name);
        if ($table_schema === null) {
            Console::stdout("Table '{$table_name}' does not appear to exist; considering create unsuccessful.\n", Console::FG_RED);
            return false;
        } else {
            return true;
        }
    }

    public function safeDown()
    {
        $table_name = $this->db->tablePrefix . 'user';

        $table_schema = Yii::$app->db->schema->getTableSchema($table_name);
        if ($table_schema === null) {
            Console::stdout("Table '{$table_name}' does not appear to exist; considering drop successful.\n", Console::FG_YELLOW);
        } else {
            Console::stdout("Dropping table '{$table_name}'.\n");
            $this->dropTable($table_name);
        }
    }

}
