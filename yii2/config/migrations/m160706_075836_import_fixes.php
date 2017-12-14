<?php

use yii\helpers\Console;
use yii\db\Migration;

class m160706_075836_import_fixes extends Migration
{

    public function safeUp()
    {
        $table_name = $this->db->tablePrefix . 'service';
        Console::stdout("Altering table '{$table_name}': Making name longer.\n");
        Yii::$app->db->createCommand("ALTER TABLE `{$table_name}` CHANGE `name` `name` VARCHAR(200) NOT NULL;")
                ->execute();

        $table_name = $this->db->tablePrefix . 'position';
        Console::stdout("Altering table '{$table_name}': Making name longer.\n");
        Yii::$app->db->createCommand("ALTER TABLE `{$table_name}` CHANGE `name` `name` VARCHAR(200) NOT NULL;")
                ->execute();
        return true;
    }

    public function safeDown()
    {
        echo "m160706_075836_import_fixes doing nothing...\n";
        return true;
    }

}
