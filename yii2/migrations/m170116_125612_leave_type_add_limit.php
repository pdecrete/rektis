<?php

use yii\helpers\Console;
use yii\db\Migration;

class m170116_125612_leave_type_add_limit extends Migration
{
    public function safeUp()
    {
		$table_name = $this->db->tablePrefix . 'leave_type';
		Console::stdout("Altering table '{$table_name}': Inserting new fields (limit, check).\n");
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `limit` SMALLINT NULL DEFAULT NULL ")
				->execute();
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `check` BOOLEAN NOT NULL DEFAULT FALSE ")
				->execute();
		return true;
    }

    public function safeDown()
    {
		$table_name = $this->db->tablePrefix . 'leave_type';
		Console::stdout("Altering table '{$table_name}': Dropping field (limit).\n");
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP `limit` ")->execute();	
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP `check` ")->execute();	
		return true;
    }
}
