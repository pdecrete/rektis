<?php

use yii\db\Migration;
use yii\helpers\Console;

class m190114_092042_alter_kaeId_datatype extends Migration
{
    public function safeUp()
    {
        $altered_table_kae = $this->db->tablePrefix . 'finance_kae';
        $altered_table_kaecredit = $this->db->tablePrefix . 'finance_kaecredit';
        $remove_kaeid_foreignkey = "ALTER TABLE " . $altered_table_kaecredit . " DROP FOREIGN KEY admapp_finance_kaecredit_ibfk_2";
        $add_kaeid_foreignkey = "ALTER TABLE " . $altered_table_kaecredit . " ADD CONSTRAINT admapp_finance_kaecredit_ibfk_2 FOREIGN KEY (kae_id) REFERENCES " . 
                                $altered_table_kae . "(kae_id) " . "ON DELETE RESTRICT ON UPDATE RESTRICT";
        $alter_command_kae = "ALTER TABLE " . $altered_table_kae . " MODIFY COLUMN `kae_id` BIGINT";
        $alter_command_kaecredit = "ALTER TABLE " . $altered_table_kaecredit . " MODIFY COLUMN `kae_id` BIGINT";
        
        Console::stdout("\n*** Removing foreign key (kae_id) in table " . $altered_table_kaecredit . " to enable data type alternation of kae_id. *** \n");
        Yii::$app->db->createCommand($remove_kaeid_foreignkey)->execute();
        
        Console::stdout("\n*** Altering data type of column (kae_id) in tables " . $altered_table_kae . " and " . $altered_table_kaecredit . ". *** \n");
        Console::stdout("SQL Commands: " . $alter_command_kae . "\n" . $alter_command_kaecredit . "\n");        
        Yii::$app->db->createCommand($alter_command_kae)->execute();
        Yii::$app->db->createCommand($alter_command_kaecredit)->execute();
        
        Console::stdout("\n*** Adding foreign key (kae_id) in table " . $altered_table_kaecredit . ". *** \n");
        Yii::$app->db->createCommand($add_kaeid_foreignkey)->execute();
    }
    
    public function safeDown()
    {
        $altered_table_kae = $this->db->tablePrefix . 'finance_kae';
        $altered_table_kaecredit = $this->db->tablePrefix . 'finance_kaecredit';
        $remove_kaeid_foreignkey = "ALTER TABLE " . $altered_table_kaecredit . " DROP FOREIGN KEY admapp_finance_kaecredit_ibfk_2";
        $add_kaeid_foreignkey = "ALTER TABLE " . $altered_table_kaecredit . " ADD CONSTRAINT admapp_finance_kaecredit_ibfk_2 FOREIGN KEY (kae_id) REFERENCES " . 
                                $altered_table_kae . "(kae_id) " . "ON DELETE RESTRICT ON UPDATE RESTRICT";
        $alter_command_kae = "ALTER TABLE " . $altered_table_kae . " MODIFY COLUMN `kae_id` INTEGER";
        $alter_command_kaecredit = "ALTER TABLE " . $altered_table_kaecredit . " MODIFY COLUMN `kae_id` INTEGER";
        
        Console::stdout("\n*** Removing foreign key (kae_id) in table " . $altered_table_kaecredit . " to enable data type alternation of kae_id. *** \n");
        Yii::$app->db->createCommand($remove_kaeid_foreignkey)->execute();
        
        Console::stdout("\n*** Altering data type of column (kae_id) in tables " . $altered_table_kae . " and " . $altered_table_kaecredit . ". *** \n");
        Console::stdout("SQL Commands: " . $alter_command_kae . "\n" . $alter_command_kaecredit . "\n");
        Yii::$app->db->createCommand($alter_command_kae)->execute();
        Yii::$app->db->createCommand($alter_command_kaecredit)->execute();
        
        Console::stdout("\n*** Adding foreign key (kae_id) in table " . $altered_table_kaecredit . ". *** \n");
        Yii::$app->db->createCommand($add_kaeid_foreignkey)->execute();
    }
}
