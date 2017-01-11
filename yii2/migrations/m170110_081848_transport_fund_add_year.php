<?php

use yii\helpers\Console;
use yii\db\Migration;

class m170110_081848_transport_fund_add_year extends Migration
{
    public function safeUp()
    {
		$table_name = $this->db->tablePrefix . 'transport_funds';
		Console::stdout("Altering table '{$table_name}': Inserting new field (year).\n");
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `year` VARCHAR(4) NULL DEFAULT NULL ")
				->execute();
		return true;
    }

    public function safeDown()
    {
		$table_name = $this->db->tablePrefix . 'transport_funds';
		Console::stdout("Altering table '{$table_name}': Dropping field (year).\n");
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP `year` ")->execute();	
		return true;
    }
}
