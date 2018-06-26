<?php

use yii\db\Migration;
use yii\helpers\Console;

class m180622_110739_addcolumn_exp_notes extends Migration
{
    public function safeUp()
    {
        $moneyDatatype = "BIGINT";
        
        $altered_table = $this->db->tablePrefix . 'finance_expenditure';
        $alter_command = "ALTER TABLE " . $altered_table . " ADD COLUMN `exp_notes` VARCHAR(400) COMMENT 'Σημειώσεις';";
        Console::stdout("\n*** Adding new column (exp_notes) in table " . $altered_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_command . "\n");
        Yii::$app->db->createCommand($alter_command)->execute();
    }

    public function safeDown()
    {
        $altered_table = $this->db->tablePrefix . 'finance_expenditure';
        
        $alter_command = "ALTER TABLE " . $altered_table . 
                         " DROP COLUMN `exp_notes`";
        Console::stdout("\n*** Dropping column (exp_notes) from table " . $altered_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_command . "\n");
        Yii::$app->db->createCommand($alter_command)->execute();        
    }

}
