<?php

use yii\db\Migration;
use yii\helpers\Console;

class m180302_071824_withdrawal_fileupload extends Migration
{
    public function safeUp()
    {
        $altered_table = $this->db->tablePrefix . 'finance_kaewithdrawal';
        $alter_command = "ALTER TABLE " . $altered_table . " ADD COLUMN `kaewithdr_decisionfile` VARCHAR(200)";
        Console::stdout("\n*** Adding new column (kaewithdr_decisionfile) in table " . $altered_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_command . "\n");
        Yii::$app->db->createCommand($alter_command)->execute();
    }

    public function safeDown()
    {
        $altered_table = $this->db->tablePrefix . 'finance_kaewithdrawal';
        $alter_command = "ALTER TABLE " . $altered_table . " DROP COLUMN `kaewithdr_decisionfile`";
        Console::stdout("\n*** Dropping column (kaewithdr_decisionfile) from table " . $altered_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_command . "\n");
        Yii::$app->db->createCommand($alter_command)->execute();
    }
}
