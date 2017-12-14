<?php

use yii\helpers\Console;
use yii\db\Migration;

class m160628_063101_assign_file_to_leave_type extends Migration
{

    public function safeUp()
    {
        $table_name = $this->db->tablePrefix . 'leave_type';
        $column_name = 'templatefilename';
        Yii::$app->db->createCommand("alter table `{$table_name}`
                    add column `{$column_name}` varchar(255) default null"
                )
                ->execute();
    }

    public function down()
    {
        $table_name = $this->db->tablePrefix . 'leave_type';
        $column_name = 'templatefilename';
        $table_schema = Yii::$app->db->schema->getTableSchema($table_name);
        if ($table_schema === null) {
            Console::stdout("Table '{$table_name}' does not appear to exist; considering action on {$table_name} successful.\n", Console::FG_YELLOW);
        } else {
            Console::stdout("Dropping column '{$column_name}'.\n");
            $this->dropColumn($table_name, $column_name);
        }
    }

}
