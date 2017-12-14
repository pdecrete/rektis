<<?php
use yii\helpers\Console;
use yii\db\Migration;

class m161004_093202_alter_tbl_service_add_email extends Migration
{
    public function safeUp()
    {
        $table_name = $this->db->tablePrefix . 'service';
        Console::stdout("Altering table '{$table_name}': Inserting new Email field.\n");
        Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `email` VARCHAR( 100 ) NULL DEFAULT NULL ")
                ->execute();
        return true;

    }

    public function safeDown()
    {
        $table_name = $this->db->tablePrefix . 'service';
        Console::stdout("Altering table '{$table_name}': Dropping Email field.\n");
        Yii::$app->db->createCommand("alter table `{$table_name}` drop column `email` ")->execute();
        return true;
    }
}
