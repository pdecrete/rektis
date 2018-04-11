<?php

use yii\db\Migration;
use yii\helpers\Console;

class m180402_062008_expendwithdrawals_order extends Migration
{
    public function safeUp()
    {
        $altered_table = $this->db->tablePrefix . 'finance_expendwithdrawal';
        $alter_command = "ALTER TABLE " . $altered_table . " ADD COLUMN `expwithdr_order` SMALLINT UNSIGNED NOT NULL COMMENT 'Σειρά αντιστοίχησης σε αναλήψεις.'";
        Console::stdout("\n*** Adding new column (expwithdr_order) in table " . $altered_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_command . "\n");
        Yii::$app->db->createCommand($alter_command)->execute();
    }

    public function safeDown()
    {
        $altered_table = $this->db->tablePrefix . 'finance_expendwithdrawal';
        $alter_command = "ALTER TABLE " . $altered_table . " DROP COLUMN `expwithdr_order`";
        Console::stdout("\n*** Dropping column (expwithdr_orderby) from table " . $altered_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_command . "\n");
        Yii::$app->db->createCommand($alter_command)->execute();
    }
}
