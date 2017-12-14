<?php

use yii\helpers\Console;
use yii\db\Migration;

class m170116_125132_leave_add_extra_reasons extends Migration
{
	public function safeUp()
    {
		$table_name = $this->db->tablePrefix . 'leave';
		Console::stdout("Altering table '{$table_name}': Inserting new fields (extra_reason1, extra_reason2, extra_reason3).\n");
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `extra_reason1` VARCHAR(250) NULL DEFAULT NULL ")
				->execute();
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `extra_reason2` VARCHAR(250) NULL DEFAULT NULL ")
				->execute();
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `extra_reason3` VARCHAR(250) NULL DEFAULT NULL ")
				->execute();
		return true;
    }

    public function safeDown()
    {
		$table_name = $this->db->tablePrefix . 'leave';
		Console::stdout("Altering table '{$table_name}': Dropping fields (extra_reason1, extra_reason2, extra_reason3).\n");
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP `extra_reason1` ")->execute();	
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP `extra_reason2` ")->execute();	
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP `extra_reason3` ")->execute();	
		return true;
    }
}
