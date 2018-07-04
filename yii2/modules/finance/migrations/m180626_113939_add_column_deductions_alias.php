<?php

use yii\db\Migration;
use yii\helpers\Console;

class m180626_113939_add_column_deductions_alias extends Migration
{
    public function safeUp()
    {
        $altered_table = $this->db->tablePrefix . 'finance_deduction';
        $alter_command = "ALTER TABLE " . $altered_table . " ADD COLUMN `deduct_alias` VARCHAR(100) NOT NULL COMMENT 'Λεκτικό κλειδί';";        
        Console::stdout("\n*** Adding new column (deduct_alias) in table " . $altered_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_command . "\n");
        Yii::$app->db->createCommand($alter_command)->execute();
        Yii::$app->db->createCommand("ALTER TABLE " . $altered_table . " ADD UNIQUE (`deduct_alias`)");
        Yii::$app->db->createCommand("UPDATE " . $altered_table . " SET deduct_alias=LOWER(REPLACE(deduct_name, ' ', '_'))")->execute();
        Yii::$app->db->createCommand("UPDATE " . $altered_table . " SET deduct_alias='services_goods_under_150euro' WHERE deduct_id=1")->execute();
        Yii::$app->db->createCommand("UPDATE " . $altered_table . " SET deduct_alias='services_over_150euro' WHERE deduct_id=2")->execute();
        Yii::$app->db->createCommand("UPDATE " . $altered_table . " SET deduct_alias='goods_over_150euro' WHERE deduct_id=3")->execute();
        Yii::$app->db->createCommand("UPDATE " . $altered_table . " SET deduct_alias='cleaning' WHERE deduct_id=4")->execute();
        
        $insert_command = "INSERT INTO " . $altered_table .
        "(deduct_id, deduct_name, deduct_alias, deduct_date, deduct_percentage, deduct_downlimit, deduct_uplimit) VALUES ";
        Yii::$app->db->createCommand($insert_command . "(10, 'Χωρίς φόρο', 'no_tax' , NOW(), 0, 0, NULL)")->execute();
        
        
    }

    public function safeDown()
    {
        $altered_table = $this->db->tablePrefix . 'finance_deduction';
        $alter_command = "ALTER TABLE " . $altered_table . " DROP COLUMN `deduct_alias`";
        Console::stdout("\n*** Dropping column (deduct_alias) from table " . $altered_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_command . "\n");
        Yii::$app->db->createCommand($alter_command)->execute();
        
        Yii::$app->db->createCommand("DELETE FROM " . $altered_table . " WHERE deduct_id=10")->execute();
    }
}
