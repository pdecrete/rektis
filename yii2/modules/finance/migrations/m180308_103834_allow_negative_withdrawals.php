<?php

use yii\db\Migration;
use yii\helpers\Console;

class m180308_103834_allow_negative_withdrawals extends Migration
{
    public function safeUp()
    {
        $moneyDatatype = "BIGINT";
        $altered_table = $this->db->tablePrefix . 'finance_kaewithdrawal';
        $alter_command = "ALTER TABLE " . $altered_table . " MODIFY COLUMN `kaewithdr_amount` " . $moneyDatatype . " NOT NULL";
        Console::stdout("\n*** Altering data type of column (kaewithdr_amount) in table " . $altered_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_command . "\n");
        Yii::$app->db->createCommand($alter_command)->execute();
    }

    public function safeDown()
    {
        $moneyDatatype = "BIGINT";
        $altered_table = $this->db->tablePrefix . 'finance_kaewithdrawal';
        $alter_command = "ALTER TABLE " . $altered_table . " MODIFY COLUMN `kaewithdr_amount` " . $moneyDatatype . " UNSIGNED NOT NULL";
        Console::stdout("\n*** Recerting data type of column (kaewithdr_amount) in table " . $altered_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_command . "\n");
        Yii::$app->db->createCommand($alter_command)->execute();
    }
}
