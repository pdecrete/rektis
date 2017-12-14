<?php

use yii\helpers\Console;
use yii\db\Migration;

class m161214_125923_transport_add_locked_flag extends Migration
{
    public function safeUp()
    {
        $table_name = $this->db->tablePrefix . 'transport';
        Console::stdout("Altering table '{$table_name}': Inserting new field (locked).\n");
        Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `locked` boolean DEFAULT 0 ")
                ->execute();
        return true;

    }

    public function safeDown()
    {
        $table_name = $this->db->tablePrefix . 'transport';
        Console::stdout("Altering table '{$table_name}': Dropping field locked.\n");
        Yii::$app->db->createCommand("alter table `{$table_name}` DROP `locked` ")->execute();
        return true;
    }
}
