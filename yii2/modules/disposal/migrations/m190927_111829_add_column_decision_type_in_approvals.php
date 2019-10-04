<?php

use yii\db\Migration;
use yii\helpers\Console;

class m190927_111829_add_column_decision_type_in_approvals extends Migration
{
    public function safeUp()
    {
        $altered_table = $this->db->tablePrefix . 'disposal_approval';
        $alter_command = "ALTER TABLE " . $altered_table . " ADD COLUMN `approval_type` TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT '1 FOR DISPOSALS_APPROVAL_GENERAL, 2 FOR COMMON_SPECILIAZATIONS_DECISION, 3 FOR EUROPEAN_SCHOOL_DECISION';";
        Console::stdout("\n*** Adding new column (approval_type) in table " . $altered_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_command . "\n");
        Yii::$app->db->createCommand($alter_command)->execute();
    }

    public function safeDown()
    {
        $altered_table = $this->db->tablePrefix . 'disposal_approval';
        $alter_command = "ALTER TABLE " . $altered_table . " DROP COLUMN `approval_type`";
        Console::stdout("\n*** Dropping column (approval_type) from table " . $altered_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_command . "\n");
        Yii::$app->db->createCommand($alter_command)->execute();
    }
}
