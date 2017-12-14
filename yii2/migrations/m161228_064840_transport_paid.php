<?php

use yii\helpers\Console;
use yii\db\Migration;

class m161228_064840_transport_paid extends Migration
{
    public function safeUp()
    {
		$table_name = $this->db->tablePrefix . 'transport';
		Console::stdout("Altering table '{$table_name}': Inserting new field (paid).\n");
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `paid` boolean DEFAULT FALSE ")
				->execute();
		$table_name = $this->db->tablePrefix . 'transport_print';
		Console::stdout("Altering table '{$table_name}': Inserting new field (paid).\n");
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `paid` boolean DEFAULT FALSE ")
				->execute();
	
		return true;
    }

    public function safeDown()
    {
		$table_name = $this->db->tablePrefix . 'transport';
		Console::stdout("Altering table '{$table_name}': Dropping field (paid).\n");
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP `paid` ")->execute();
		$table_name = $this->db->tablePrefix . 'transport_print';
		Console::stdout("Altering table '{$table_name}': Dropping field (paid).\n");
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP `paid` ")->execute();
		
		return true;
    }
}
