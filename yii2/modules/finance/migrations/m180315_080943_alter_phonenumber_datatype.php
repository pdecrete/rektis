<?php

use yii\db\Migration;
use yii\helpers\Console;

class m180315_080943_alter_phonenumber_datatype extends Migration
{
    public function safeUp()
    {
        $altered_table = $this->db->tablePrefix . 'finance_supplier';
        $alter_command1 = "ALTER TABLE " . $altered_table . " MODIFY COLUMN `suppl_phone` VARCHAR(30)";
        $alter_command2 = "ALTER TABLE " . $altered_table . " MODIFY COLUMN `suppl_fax` VARCHAR(30)";
        Console::stdout("\n*** Altering data type of columns (suppl_phone, suppl_fax) in table " . $altered_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_command1 . "\n");
        Yii::$app->db->createCommand($alter_command1)->execute();
        Console::stdout("SQL Command: " . $alter_command2 . "\n");
        Yii::$app->db->createCommand($alter_command2)->execute();
    }

    public function safeDown()
    {
        $altered_table = $this->db->tablePrefix . 'finance_supplier';
        $alter_command1 = "ALTER TABLE " . $altered_table . " MODIFY COLUMN `suppl_phone` INTEGER";
        $alter_command2 = "ALTER TABLE " . $altered_table . " MODIFY COLUMN `suppl_fax` INTEGER";
        Console::stdout("\n*** Altering data type of columns (suppl_phone, suppl_fax) in table " . $altered_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_command1 . "\n");
        Yii::$app->db->createCommand($alter_command1)->execute();
        Console::stdout("SQL Command: " . $alter_command2 . "\n");
        Yii::$app->db->createCommand($alter_command2)->execute();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180315_080943_alter_phonenumber_datatype cannot be reverted.\n";

        return false;
    }
    */
}
