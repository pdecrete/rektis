<?php

use yii\helpers\Console;
use yii\db\Migration;

class m170109_091629_transport_nights_out_add extends Migration
{
    public function safeUp()
    {
		$table_name = $this->db->tablePrefix . 'transport';
		Console::stdout("Altering table '{$table_name}': Inserting new field (nights_out).\n");
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `nights_out` smallint NULL DEFAULT 0 ")
				->execute();
		return true;
    }

    public function safeDown()
    {
		$table_name = $this->db->tablePrefix . 'transport';
		Console::stdout("Altering table '{$table_name}': Dropping field (nights_out).\n");
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP `nights_out` ")->execute();	
		return true;
    }

}
