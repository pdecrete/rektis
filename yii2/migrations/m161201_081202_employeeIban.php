<?php

use yii\helpers\Console;
use yii\db\Migration;

class m161201_081202_employeeIban extends Migration
{
    public function safeUp()
    {
        $table_name = $this->db->tablePrefix . 'employee';
        Console::stdout("Altering table '{$table_name}': Inserting new field [iban].\n");
        Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `iban` VARCHAR(27) NULL DEFAULT NULL ")
                ->execute();
        return true;

    }

    public function safeDown()
    {
        $table_name = $this->db->tablePrefix . 'employee';
        Console::stdout("Altering table '{$table_name}': Dropping field [iban].\n");
        Yii::$app->db->createCommand("alter table `{$table_name}` drop column `iban` ")->execute();
        return true;
    }
}
