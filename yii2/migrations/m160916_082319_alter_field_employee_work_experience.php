<?php
use yii\helpers\Console;
use yii\db\Migration;

class m160916_082319_alter_field_employee_work_experience extends Migration
{
    public function safeUp()
    {
	$table_name = $this->db->tablePrefix . 'employee';
        Console::stdout("Altering table '{$table_name}': Altering field work_experience - declaring it INT.\n");
        Yii::$app->db->createCommand("
		alter table `{$table_name}` change `work_experience` `work_experience` INT( 11 ) NULL DEFAULT NULL COMMENT 'προυπηρεσια σε ημερες'
		 ")
                ->execute();
            return true;
    }

    public function safeDown()
    {
	$table_name = $this->db->tablePrefix . 'employee';
        Console::stdout("Altering table '{$table_name}': Altering field work_experience - declaring it unsigned int.\n");
        Yii::$app->db->createCommand("
		alter table `{$table_name}` change `work_experience` `work_experience` INT( 10 ) UNSIGNED NULL DEFAULT NULL COMMENT 'προυπηρεσια σε ημερες'
		 ")
                ->execute();
            return true;
    }
}
