<?php

use yii\helpers\Console;
use yii\db\Migration;

class m161213_071743_transport_print_add_fields extends Migration
{
    public function safeUp()
    {
		$table_name = $this->db->tablePrefix . 'transport_print';
		Console::stdout("Altering table '{$table_name}': Inserting new fields (from, to, sum719, sum721, sum722, sum_mtpy, total, clean, asum719, asum721, asum722, asum_mtpy, atotal, aclean).\n");
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `from` DATE DEFAULT NULL ")
				->execute();
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `to` DATE DEFAULT NULL ")
				->execute();
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `sum719` DECIMAL(10,2) DEFAULT NULL ")
				->execute();
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `sum721` DECIMAL(10,2) DEFAULT NULL ")
				->execute();
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `sum722` DECIMAL(10,2) DEFAULT NULL ")
				->execute();
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `sum_mtpy` DECIMAL(10,2) DEFAULT NULL ")
				->execute();
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `total` DECIMAL(10,2) DEFAULT NULL ")
				->execute();
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `clean` DECIMAL(10,2) DEFAULT NULL ")
				->execute();
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `asum719` DECIMAL(10,2) DEFAULT NULL ")
				->execute();
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `asum721` DECIMAL(10,2) DEFAULT NULL ")
				->execute();
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `asum722` DECIMAL(10,2) DEFAULT NULL ")
				->execute();
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `asum_mtpy` DECIMAL(10,2) DEFAULT NULL ")
				->execute();
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `atotal` DECIMAL(10,2) DEFAULT NULL ")
				->execute();
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `aclean` DECIMAL(10,2) DEFAULT NULL ")
				->execute();

		return true;
    }

    public function safeDown()
    {
		$table_name = $this->db->tablePrefix . 'transport_print';
		Console::stdout("Altering table '{$table_name}': Dropping fields (from, to, sum719, sum721, sum722, sum_mtpy, total, clean, asum719, asum721, asum722, asum_mtpy, atotal, aclean).\n");
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP `from` ")->execute();
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP `to` ")->execute();
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP `sum719` ")->execute();
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP `sum721` ")->execute();
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP `sum722` ")->execute();
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP `sum_mtpy` ")->execute();
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP `total` ")->execute();
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP `clean` ")->execute();
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP `asum719` ")->execute();
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP `asum721` ")->execute();
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP `asum722` ")->execute();
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP `asum_mtpy` ")->execute();
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP `atotal` ")->execute();
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP `aclean` ")->execute();
		
		return true;
    }

}
