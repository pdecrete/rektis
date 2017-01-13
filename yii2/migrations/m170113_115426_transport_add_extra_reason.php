<?php

use yii\helpers\Console;
use yii\db\Migration;

class m170113_115426_transport_add_extra_reason extends Migration
{
	public function safeUp()
    {
		$table_name = $this->db->tablePrefix . 'transport';
		Console::stdout("Altering table '{$table_name}': Inserting new field (extra_reason).\n");
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `extra_reason` VARCHAR(200) NULL DEFAULT NULL ")
				->execute();
		return true;
    }

    public function safeDown()
    {
		$table_name = $this->db->tablePrefix . 'transport';
		Console::stdout("Altering table '{$table_name}': Dropping field (extra_reason).\n");
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP `extra_reason` ")->execute();	
		return true;
    }
}
