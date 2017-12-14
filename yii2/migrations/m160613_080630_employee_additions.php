<?php

use yii\helpers\Console;
use yii\db\Migration;

class m160613_080630_employee_additions extends Migration
{

    public function up()
    {
        $table_name = $this->db->tablePrefix . 'employee';
        Console::stdout("Altering table '{$table_name}': Adding mobile & deleted fields.\n");

        Yii::$app->db->createCommand("ALTER TABLE `admapp_employee` ADD `mobile` VARCHAR(40) NULL AFTER `telephone`;")->execute();
        Yii::$app->db->createCommand("ALTER TABLE `admapp_employee` ADD `deleted` BOOLEAN NOT NULL DEFAULT FALSE AFTER `update_ts`;")->execute();
    }

    public function down()
    {
        Console::stdout("Altering table 'admapp_employee': Dropping mobile & deleted fields.\n");

        Yii::$app->db->createCommand("ALTER TABLE `admapp_employee` DROP COLUMN `mobile`;")->execute();
        Yii::$app->db->createCommand("ALTER TABLE `admapp_employee` DROP COLUMN `deleted`;")->execute();
    }

}
