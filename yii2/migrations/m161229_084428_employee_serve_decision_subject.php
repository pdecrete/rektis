<?php

use yii\helpers\Console;
use yii\db\Migration;

class m161229_084428_employee_serve_decision_subject extends Migration
{
    public function safeUp()
    {
		$table_name = $this->db->tablePrefix . 'employee';
		Console::stdout("Altering table '{$table_name}': Inserting new field (serve_decision_subject).\n");
		Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `serve_decision_subject` varchar(200) NULL DEFAULT NULL ")
				->execute();
		return true;
    }

    public function safeDown()
    {
		$table_name = $this->db->tablePrefix . 'employee';
		Console::stdout("Altering table '{$table_name}': Dropping field (serve_decision_subject).\n");
		Yii::$app->db->createCommand("alter table `{$table_name}` DROP `serve_decision_subject` ")->execute();	
		return true;
    }
}
