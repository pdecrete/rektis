<?php

use yii\db\Migration;

class m181122_071616_add_category_InternationalPartnterships extends Migration
{
    public function safeUp()
    {
        $prog_categ_tbl = $this->db->tablePrefix . 'schtransport_programcategory';
        $insert_command = "INSERT INTO " . $prog_categ_tbl . "(programcategory_programalias, programcategory_programtitle, programcategory_programdescription, programcategory_programparent) VALUES ";
        Yii::$app->db->createCommand($insert_command . "('INTERNATIONAL_PARTNERSHIPS', 'Διεθνής Συνεργασία', '', 'INTERNATIONAL')")->execute();
    }

    public function safeDown()
    {
        $prog_categ_tbl = $this->db->tablePrefix . 'schtransport_programcategory';
        $delete_command = "DELETE FROM " . $prog_categ_tbl . " WHERE programcategory_programalias LIKE 'INTERNATIONAL_PARTNERSHIPS'";
        Yii::$app->db->createCommand($delete_command)->execute();
    }
}
