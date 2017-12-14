<?php

use yii\helpers\Console;
use yii\db\Migration;

class m170119_113301_leave_type_add_reasonNum extends Migration
{
    public function safeUp()
    {
		$table_name = $this->db->tablePrefix . 'leave_type';
		Console::stdout("Altering table '{$table_name}': Inserting new field (reason_num).\n");
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `reason_num` SMALLINT NULL DEFAULT NULL ")
				->execute();
		return true;
    }

    public function safeDown()
    {
		$table_name = $this->db->tablePrefix . 'leave_type';
		Console::stdout("Altering table '{$table_name}': Dropping field (reason_num).\n");
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP `reason_num` ")->execute();	
		return true;
    }
}
