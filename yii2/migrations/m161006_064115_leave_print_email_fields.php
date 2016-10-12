<?php
use yii\helpers\Console;
use yii\db\Migration;

class m161006_064115_leave_print_email_fields extends Migration
{
    public function safeUp()
    {
        $table_name = $this->db->tablePrefix . 'leave_print';
        Console::stdout("Altering table '{$table_name}': Inserting new fields (send_ts, to_emails).\n");
        Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `send_ts` TIMESTAMP NULL DEFAULT NULL")
                ->execute();
        Yii::$app->db->createCommand(" Alter table `{$table_name}` ADD `to_emails` VARCHAR( 1000 ) NULL DEFAULT NULL")
                ->execute();
        return true;

    }

    public function safeDown()
    {
        $table_name = $this->db->tablePrefix . 'leave_print';
        Console::stdout("Altering table '{$table_name}': Dropping fields send_ts, to_emails.\n");
        Yii::$app->db->createCommand("alter table `{$table_name}` DROP `send_ts`  ")->execute();
        Yii::$app->db->createCommand("alter table `{$table_name}` DROP `to_emails`  ")->execute();
        return true;
    }
}
