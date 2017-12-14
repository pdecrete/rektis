<?php

use yii\helpers\Console;
use yii\db\Migration;

class m161212_104712_transport_type_add_filename4 extends Migration
{
    public function safeUp()
    {
        $table_name = $this->db->tablePrefix . 'transport_type';
        Console::stdout("Altering table '{$table_name}': Inserting new field (templatefilename4).\n");
        Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `templatefilename4` varchar(255) DEFAULT NULL ")
                ->execute();
        return true;

    }

    public function safeDown()
    {
        $table_name = $this->db->tablePrefix . 'transport_type';
        Console::stdout("Altering table '{$table_name}': Dropping field templatefilename4.\n");
        Yii::$app->db->createCommand("alter table `{$table_name}` DROP `templatefilename4` ")->execute();
        return true;
    }
}
