<?php

use yii\db\Migration;
use yii\helpers\Console;

class m181018_083327_add_column_SchoolMineduCode extends Migration
{
    public function safeUp()
    {
        $altered_table = $this->db->tablePrefix . 'schoolunit';
        $alter_command = "ALTER TABLE " . $altered_table . " ADD COLUMN `school_mineducode` VARCHAR(50) COMMENT 'Κωδικός σχολείου από ΥΠΠΕΘ';"; /*VARCHAR BECAUSE THERE ARE I.E. SEK026 CODES*/
        $alter_columnunique_command =  "ALTER TABLE " . $altered_table . " ADD CONSTRAINT school_mineducode UNIQUE (school_mineducode)";
        Console::stdout("\n*** Adding new column (school_mineducode) in table " . $altered_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_command . "\n");
        Yii::$app->db->createCommand($alter_command)->execute();
        Yii::$app->db->createCommand($alter_columnunique_command)->execute();        
    }
    
    public function safeDown()
    {
        $altered_table = $this->db->tablePrefix . 'schoolunit';
        $alter_command = "ALTER TABLE " . $altered_table . " DROP COLUMN `school_mineducode`";
        Console::stdout("\n*** Dropping column (school_mineducode) from table " . $altered_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_command . "\n");
        Yii::$app->db->createCommand($alter_command)->execute();
    }
}
