<?php

use yii\helpers\Console;
use yii\db\Migration;

class m161219_083433_transport_print_more_fields extends Migration
{
    public function safeUp()
    {
		$table_name = $this->db->tablePrefix . 'transport_print';
		Console::stdout("Altering table '{$table_name}': Inserting new fields (whole_amount, report_prot, report_date, report_num, report_year).\n");
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `whole_amount` VARCHAR(100) DEFAULT NULL ")
				->execute();
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `report_prot` INTEGER DEFAULT NULL ")
				->execute();
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `report_date` DATE DEFAULT NULL ")
				->execute();		
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `report_num` SMALLINT DEFAULT NULL ")
				->execute();
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `report_year` VARCHAR(10) DEFAULT NULL ")
				->execute();
		return true;
    }

    public function safeDown()
    {
		$table_name = $this->db->tablePrefix . 'transport_print';
		Console::stdout("Altering table '{$table_name}': Dropping fields (whole_amount, report_prot, report_date, report_num, report_year).\n");
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP `whole_amount` ")->execute();
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP `report_prot` ")->execute();
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP `report_date` ")->execute();
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP `report_num` ")->execute();
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP `report_year` ")->execute();
		
		return true;
    }
}
