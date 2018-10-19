<?php

use yii\db\Migration;
use yii\helpers\Console;

class m181005_082151_add_column_SchoolunitStatus extends Migration
{
    public function safeUp()
    {
        $altered_table = $this->db->tablePrefix . 'schoolunit';
        $alter_command = "ALTER TABLE " . $altered_table . " ADD COLUMN `school_state` TINYINT NOT NULL DEFAULT 1 COMMENT 'Λειτουργική Κατάσταση';";
        Console::stdout("\n*** Adding new column (school_state) in table " . $altered_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_command . "\n");
        Yii::$app->db->createCommand($alter_command)->execute();
        Yii::$app->db->createCommand("ALTER TABLE " . $altered_table . " DROP INDEX school_name")->execute();
        //Yii::$app->db->createCommand("ALTER TABLE " . $altered_table . " ADD CONSTRAINT school_unique_at_directorate UNIQUE (`school_name`, `school_state`, `directorate_id`)")->execute();
    }

    public function safeDown()
    {
        $altered_table = $this->db->tablePrefix . 'schoolunit';
        //Yii::$app->db->createCommand("ALTER TABLE " . $altered_table . " DROP INDEX school_unique_at_directorate")->execute();
        Yii::$app->db->createCommand("ALTER TABLE " . $altered_table . " ADD UNIQUE (`school_name`, `directorate_id`, `school_id`)")->execute(); 
        //school_id was added to the initial school_name unique constraint to avoid integrity constraint violation of already inserted schools after dropping school_state column 
        $alter_command = "ALTER TABLE " . $altered_table . " DROP COLUMN `school_state`";
        Console::stdout("\n*** Dropping column (school_state) from table " . $altered_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_command . "\n");
        Yii::$app->db->createCommand($alter_command)->execute();
    }
}
