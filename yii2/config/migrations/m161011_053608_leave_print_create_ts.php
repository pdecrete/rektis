<?php
use yii\helpers\Console;
use yii\db\Migration;

class m161011_053608_leave_print_create_ts extends Migration
{
 public function safeUp()
    {
        $table_name = $this->db->tablePrefix . 'leave_print';
        Console::stdout("Altering table '{$table_name}': Change field create_ts - shouldn't take current timestamp on update.\n");
        Yii::$app->db->createCommand(" Alter table `{$table_name}` CHANGE `create_ts` `create_ts` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP")
                ->execute();
        return true;
    }

    public function safeDown()
    {
        $table_name = $this->db->tablePrefix . 'leave_print';
        Console::stdout("Altering table '{$table_name}': Change field create_ts - take current timestamp on update.\n");
        Yii::$app->db->createCommand(" Alter table `{$table_name}`  CHANGE `create_ts` `create_ts` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ")
                ->execute();
        return true;
    }
  
}

