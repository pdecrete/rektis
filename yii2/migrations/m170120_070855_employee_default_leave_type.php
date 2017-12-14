<?php

use yii\helpers\Console;
use yii\db\Migration;

class m170120_070855_employee_default_leave_type extends Migration
{
    public function safeUp()
    {
		$table_name = $this->db->tablePrefix . 'employee';
		Console::stdout("Altering table '{$table_name}': Inserting new field (default_leave_type).\n");
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `default_leave_type` INTEGER NULL DEFAULT NULL ")
				->execute();
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD KEY `default_leave_type_fk_index` (`default_leave_type`) ")
				->execute();
		$this->addForeignKey('default_leave_type_fk', $table_name, 'default_leave_type', 'admapp_leave_type', 'id', 'SET NULL', 'CASCADE');
		return true;

    }

    public function safeDown()
    {
		$table_name = $this->db->tablePrefix . 'employee';
		Console::stdout("Altering table '{$table_name}': Dropping field (default_leave_type).\n");
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP FOREIGN KEY `default_leave_type_fk` ")->execute();	
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP INDEX `default_leave_type_fk_index` ")->execute();	
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP `default_leave_type` ")->execute();	
		return true;
    }
}
