<?php

use yii\helpers\Console;
use yii\db\Migration;
use yii\db\Expression;

class m160616_065412_leave_prints extends Migration
{

    public function safeUp()
    {
        $leaves_table_name = $this->db->tablePrefix . 'leave';
        $prints_table_name = $this->db->tablePrefix . 'leave_print';

        Console::stdout("Creating leave print table.\n", Console::FG_YELLOW);
        Yii::$app->db->createCommand("
create table if not exists `{$prints_table_name}` (
    `id` integer not null auto_increment,
    `leave` integer comment 'Άδεια',
    index `leave_fk_index` (`leave`),
    constraint `fk_leave`
        foreign key `leave_fk_index` (`leave`)
        references `{$leaves_table_name}` (`id`)
        on update cascade
        on delete set null,
    `filename` varchar(255) not null,
    `create_ts` timestamp not null,
    primary key (`id`),
    unique index leave_print_filename (`filename`)
) engine=InnoDB charset=utf8;
		")
                ->execute();

        $table_schema = Yii::$app->db->schema->getTableSchema($prints_table_name);
        if ($table_schema === null) {
            Console::stdout("Table '{$prints_table_name}' does not appear to exist; considering create unsuccessful.\n", Console::FG_RED);
            return false;
        }

        return true;
    }

    public function safeDown()
    {
        $prints_table_name = $this->db->tablePrefix . 'leave_print';

        $table_schema = Yii::$app->db->schema->getTableSchema($prints_table_name);
        if ($table_schema === null) {
            Console::stdout("Table '{$table_name}' does not appear to exist; considering drop successful.\n", Console::FG_YELLOW);
        } else {
            Console::stdout("Dropping table '{$prints_table_name}'.\n");
            $this->dropTable($prints_table_name);
        }
    }

}
