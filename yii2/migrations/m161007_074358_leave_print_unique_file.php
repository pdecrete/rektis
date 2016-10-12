<?php
use yii\helpers\Console;
use yii\db\Migration;

class m161007_074358_leave_print_unique_file extends Migration
{
    public function safeUp()
    {
        $table_name = $this->db->tablePrefix . 'leave_print';
        Console::stdout("Altering table '{$table_name}': Deleting unique filename index.\n");
        Yii::$app->db->createCommand(" Alter table `{$table_name}` DROP INDEX `leave_print_filename`")
                ->execute();

        return true;
    }

    public function safeDown()
    {
        $table_name = $this->db->tablePrefix . 'leave_print';
        Console::stdout("Altering table '{$table_name}': Creating unique filename index.\n");
        Yii::$app->db->createCommand("alter table `{$table_name}` ADD CONSTRAINT `leave_print_filename` UNIQUE (`filename`)  ")->execute();
        return true;
    }


}
