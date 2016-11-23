<?php
use yii\helpers\Console;
use yii\db\Migration;

class m161101_114832_employee_adoption extends Migration
{
    public function safeUp()
    {
        $table_name = $this->db->tablePrefix . 'employee';
        Console::stdout("Altering table '{$table_name}': Inserting new fields [serve_decision, serve_decision_date, work_base, home_base].\n");
        Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `serve_decision` VARCHAR( 100 ) NULL DEFAULT NULL ")
                ->execute();
        Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `serve_decision_date` DATE NULL DEFAULT NULL ")
                ->execute();
        Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `work_base` VARCHAR( 100 ) NULL DEFAULT NULL ")
                ->execute();
        Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `home_base` VARCHAR( 100 ) NULL DEFAULT NULL ")
                ->execute();
        return true;

    }

    public function safeDown()
    {
        $table_name = $this->db->tablePrefix . 'employee';
        Console::stdout("Altering table '{$table_name}': Dropping fields [serve_decision, serve_decision_date, work_base, home_base].\n");
        Yii::$app->db->createCommand("alter table `{$table_name}` drop column `serve_decision` ")->execute();
        Yii::$app->db->createCommand("alter table `{$table_name}` drop column `serve_decision_date` ")->execute();
        Yii::$app->db->createCommand("alter table `{$table_name}` drop column `work_base` ")->execute();
        Yii::$app->db->createCommand("alter table `{$table_name}` drop column `home_base` ")->execute();
        return true;
    }
}
