<?php

use yii\db\Migration;
use yii\helpers\Console;

class m180718_083956_alter_expdate_to_datetime extends Migration
{
    public function safeUp()
    {
        $altered_table = $this->db->tablePrefix . 'finance_expenditure';
        $alter_command = "ALTER TABLE " . $altered_table . " MODIFY COLUMN `exp_date` DATETIME NOT NULL";
        Console::stdout("\n*** Altering data type of columns (exp_date) in table " . $altered_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_command . "\n");
        Yii::$app->db->createCommand($alter_command)->execute();
    }
    
    public function safeDown()
    {
        $altered_table = $this->db->tablePrefix . 'finance_expenditure';
        $alter_command = "ALTER TABLE " . $altered_table . " MODIFY COLUMN `exp_date` DATE NOT NULL";
        Console::stdout("\n*** Altering data type of columns (exp_date) in table " . $altered_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_command . "\n");
        Yii::$app->db->createCommand($alter_command)->execute();
    }
}