<?php

use yii\db\Migration;
use yii\helpers\Console;

class m190122_091503_add_column_disposals_ordering_in_approvals extends Migration
{
    public function safeUp()
    {
        $altered_table = $this->db->tablePrefix . 'disposal_disposalapproval';
        $alter_command = "ALTER TABLE " . $altered_table . " ADD COLUMN `disposalapproval_order` INTEGER NOT NULL COMMENT 'Σειρά διάθεσης στην έγκριση';";
        Console::stdout("\n*** Adding new column (disposalapproval_order) in table " . $altered_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_command . "\n");
        Yii::$app->db->createCommand($alter_command)->execute();
    }
    
    public function safeDown()
    {
        $altered_table = $this->db->tablePrefix . 'disposal_disposalapproval';
        $alter_command = "ALTER TABLE " . $altered_table . " DROP COLUMN `disposalapproval_order`";
        Console::stdout("\n*** Dropping column (disposalapproval_order) from table " . $altered_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_command . "\n");
        Yii::$app->db->createCommand($alter_command)->execute();
    }
}
