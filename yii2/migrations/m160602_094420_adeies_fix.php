<?php

use yii\helpers\Console;
use yii\db\Migration;

class m160602_094420_adeies_fix extends Migration
{

    public function safeUp()
    {
        $types_table_name = $this->db->tablePrefix . 'leave_type';
        $table_name = $this->db->tablePrefix . 'leave';

        Yii::$app->db->createCommand("alter table `{$types_table_name}`
                    add column `deleted` tinyint (1) not null default 0"
                )
                ->execute();

        Yii::$app->db->createCommand("alter table `{$types_table_name}`
                    add index `{$types_table_name}_by_deleted` (`deleted`) "
                )
                ->execute();

        Yii::$app->db->createCommand("alter table `{$table_name}`
                    add column `deleted` tinyint (1) not null default 0"
                )
                ->execute();

        Yii::$app->db->createCommand("alter table `{$table_name}`
                    add index `{$table_name}_by_deleted` (`deleted`) "
                )
                ->execute();
    }

    public function safeDown()
    {
        $column_name = 'deleted';
        $types_table_name = $this->db->tablePrefix . 'leave_type';
        $table_name = $this->db->tablePrefix . 'leave';

        $table_schema = Yii::$app->db->schema->getTableSchema($table_name);
        if ($table_schema === null) {
            Console::stdout("Table '{$table_name}' does not appear to exist; considering action on {$table_name} successful.\n", Console::FG_YELLOW);
        } else {
            Console::stdout("Dropping column '{$column_name}'.\n");
            $this->dropColumn($table_name, $column_name);
        }

        $table_schema2 = Yii::$app->db->schema->getTableSchema($types_table_name);
        if ($table_schema2 === null) {
            Console::stdout("Table '{$types_table_name}' does not appear to exist; considering action on {$types_table_name} successful.\n", Console::FG_YELLOW);
        } else {
            Console::stdout("Dropping column '{$column_name}'.\n");
            $this->dropColumn($types_table_name, $column_name);
        }
    }

}
