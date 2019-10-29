<?php

use yii\db\Migration;
use yii\helpers\Console;

class m191021_093729_add_leave_field_calendar_or_school_year_flag extends Migration
{
    public function safeUp()
    {
        $altered_table = $this->db->tablePrefix . 'leave_type';
        $alter_command = "ALTER TABLE " . $altered_table . " ADD COLUMN `schoolyear_based` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '0 FOR LEAVES SUMMING UP ON CALENAR YEAR PERIOD, 1 FOR LEAVES SUMMING UP ON TEACHING YEAR PERIOD';";
        Console::stdout("\n*** Adding new column (schoolyear_based) in table " . $altered_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_command . "\n");
        Yii::$app->db->createCommand($alter_command)->execute();
    }
    
    public function safeDown()
    {
        $altered_table = $this->db->tablePrefix . 'leave_type';
        $alter_command = "ALTER TABLE " . $altered_table . " DROP COLUMN `schoolyear_based`";
        Console::stdout("\n*** Dropping column (schoolyear_based) from table " . $altered_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_command . "\n");
        Yii::$app->db->createCommand($alter_command)->execute();
    }
}
