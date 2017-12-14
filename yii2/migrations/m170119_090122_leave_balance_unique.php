<?php

use yii\helpers\Console;
use yii\db\Migration;

class m170119_090122_leave_balance_unique extends Migration
{
    public function safeUp()
    {
		$table_name = $this->db->tablePrefix . 'leave_balance';
		Console::stdout("Altering table '{$table_name}': Adding unique index (employee, leavetype, year).\n");
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD UNIQUE `unique_index`(`employee`, `leave_type`, `year`) ")
				->execute();
		return true;
    }

    public function safeDown()
    {
		$table_name = $this->db->tablePrefix . 'leave_balance';
		Console::stdout("Altering table '{$table_name}': Dropping unique index (employee, leavetype, year).\n");
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP index `unique_index` ")->execute();	
		return true;
    }
}
