<?php

use yii\db\Migration;
use yii\helpers\Console;

class m180719_113609_add_field_exp_flattaxes extends Migration
{
    public function safeUp()
    {
        $altered_table = $this->db->tablePrefix . 'finance_expenditure';
        $alter_command = "ALTER TABLE " . $altered_table . " ADD COLUMN `exp_flattaxes` VARCHAR(400) COMMENT 'Σταθεροί φόροι';";
        Console::stdout("\n*** Adding new column (exp_flattaxes) in table " . $altered_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_command . "\n");
        Yii::$app->db->createCommand($alter_command)->execute();
    }
    
    public function safeDown()
    {
        $altered_table = $this->db->tablePrefix . 'finance_expenditure';
        $alter_command = "ALTER TABLE " . $altered_table . " DROP COLUMN `exp_flattaxes`";
        Console::stdout("\n*** Dropping column (exp_flattaxes) from table " . $altered_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_command . "\n");
        Yii::$app->db->createCommand($alter_command)->execute();
    }   
}