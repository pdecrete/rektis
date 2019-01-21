<?php

use yii\db\Migration;
use yii\helpers\Console;

class m190121_103546_alter_students_field_size extends Migration
{
    public function safeUp()
    {
        $altered_table = $this->db->tablePrefix . 'schtransport_transport';
        $alter_command = "ALTER TABLE " . $altered_table . " MODIFY COLUMN `transport_students` VARCHAR(5000)";
        Console::stdout("\n*** Altering data type of columns (transport_students) in table " . $altered_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_command . "\n");
        Yii::$app->db->createCommand($alter_command)->execute();
    }
    
    public function safeDown()
    {
        $altered_table = $this->db->tablePrefix . 'schtransport_transport';
        $alter_command = "ALTER TABLE " . $altered_table . " MODIFY COLUMN `transport_students` VARCHAR(2000)";
        Console::stdout("\n*** Altering data type of columns (suppl_phone, suppl_fax) in table " . $altered_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_command . "\n");
        Yii::$app->db->createCommand($alter_command)->execute();
    }
}
