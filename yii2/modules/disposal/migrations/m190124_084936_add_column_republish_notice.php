<?php

use yii\db\Migration;
use yii\helpers\Console;

class m190124_084936_add_column_republish_notice extends Migration
{
    public function safeUp()
    {
        $altered_table = $this->db->tablePrefix . 'disposal_approval';
        $alter_command_text = "ALTER TABLE " . $altered_table . " ADD COLUMN `approval_republishtext` VARCHAR(2000) DEFAULT NULL COMMENT 'Λόγος ανακοινοποίησης';";
        $alter_command_date = "ALTER TABLE " . $altered_table . " ADD COLUMN `approval_republishdate` DATE DEFAULT NULL COMMENT 'Ημερομηνία ανακοινοποίησης';";
        Console::stdout("\n*** Adding new columns (approval_republishtext, approval_republishdate) in table " . $altered_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_command_text . "\n");
        Console::stdout("SQL Command: " . $alter_command_date . "\n");
        Yii::$app->db->createCommand($alter_command_text)->execute();
        Yii::$app->db->createCommand($alter_command_date)->execute();
    }
    
    public function safeDown()
    {
        $altered_table = $this->db->tablePrefix . 'disposal_approval';
        $alter_command_text = "ALTER TABLE " . $altered_table . " DROP COLUMN `approval_republishtext`";
        $alter_command_date = "ALTER TABLE " . $altered_table . " DROP COLUMN `approval_republishdate`";
        Console::stdout("\n*** Dropping columns (approval_republishtext, approval_republishdate) from table " . $altered_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_command_text . "\n");
        Console::stdout("SQL Command: " . $alter_command_date . "\n");
        Yii::$app->db->createCommand($alter_command_text)->execute();
        Yii::$app->db->createCommand($alter_command_date)->execute();
    }
}
