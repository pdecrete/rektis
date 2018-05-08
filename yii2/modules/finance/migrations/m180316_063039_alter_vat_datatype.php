<?php

use yii\db\Migration;
use yii\helpers\Console;

class m180316_063039_alter_vat_datatype extends Migration
{
    public function safeUp()
    {
        $altered_table = $this->db->tablePrefix . 'finance_supplier';
        $alter_command = "ALTER TABLE " . $altered_table . " MODIFY COLUMN `suppl_vat` VARCHAR(30)";
        Console::stdout("\n*** Altering data type of columns (suppl_vat) in table " . $altered_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_command . "\n");
        Yii::$app->db->createCommand($alter_command)->execute();
    }

    public function safeDown()
    {
        $altered_table = $this->db->tablePrefix . 'finance_supplier';
        $alter_command = "ALTER TABLE " . $altered_table . " MODIFY COLUMN `suppl_vat` INTEGER";
        Console::stdout("\n*** Altering data type of columns (suppl_vat) in table " . $altered_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_command . "\n");
        Yii::$app->db->createCommand($alter_command)->execute();
    }
}
