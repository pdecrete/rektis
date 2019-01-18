<?php

use yii\db\Migration;
use yii\helpers\Console;

class m181218_105732_alter_localdirdecision_protocol_index extends Migration
{
    public function safeUp()
    {
        $altered_table = $this->db->tablePrefix . 'disposal_localdirdecision';
        $alter_command_drop = "ALTER TABLE " . $altered_table . " DROP INDEX localdirdecision_protocol;";
        $alter_command_add = "ALTER TABLE " . $altered_table . " ADD UNIQUE (`localdirdecision_protocol`, `localdirdecision_action`, `directorate_id`);";
        Console::stdout("\n*** Altering localdirdecision_protocol index in table " . $altered_table . ". *** \n");
        Console::stdout("SQL Commands: 1)" . $alter_command_drop . "\n2)" . $alter_command_add . "\n");
        Yii::$app->db->createCommand($alter_command_drop)->execute();
        Yii::$app->db->createCommand($alter_command_add)->execute();
    }

    public function safeDown()
    {
        $altered_table = $this->db->tablePrefix . 'disposal_localdirdecision';
        $alter_command_drop = "ALTER TABLE " . $altered_table . " DROP INDEX localdirdecision_protocol;";
        $alter_command_add = "ALTER TABLE " . $altered_table . " ADD UNIQUE (`localdirdecision_protocol`,`directorate_id`);";
        Console::stdout("\n*** Reverting localdirdecision_protocol index in table " . $altered_table . ". *** \n");
        Console::stdout("SQL Commands: 1)" . $alter_command_drop . "\n2)" . $alter_command_add . "\n");
        Yii::$app->db->createCommand($alter_command_drop)->execute();
        Yii::$app->db->createCommand($alter_command_add)->execute();
    }
}
