<?php

use yii\db\Migration;

class m190522_112249_disposalduty_notdefined_record_insert extends Migration
{
    public function safeUp()
    {
        $insert_command = "INSERT INTO " . $this->db->tablePrefix . 'disposal_disposalworkobj' . "(disposalworkobj_name, disposalworkobj_description) VALUES ";
        Yii::$app->db->createCommand($insert_command . "('not_defined', 'Μη ορισμένο')")->execute();
    }

    public function safeDown()
    {
        $delete_command = "DELETE FROM " . $this->db->tablePrefix . 'disposal_disposalworkobj'  . " WHERE disposalworkobj_name LIKE 'not_defined'";
        Yii::$app->db->createCommand($delete_command)->execute();
    }
}
