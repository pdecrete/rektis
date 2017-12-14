<?php

use yii\helpers\Console;
use yii\db\Migration;

class m160603_064058_employee_enhancements extends Migration
{

    public function up()
    {
        $table_name = $this->db->tablePrefix . 'employee';
        Console::stdout("Altering table '{$table_name}': Removing NULL from selected fields.\n");

        Yii::$app->db->createCommand("ALTER TABLE `admapp_employee` CHANGE `mothersname` `mothersname` VARCHAR(100) NULL, CHANGE `email` `email` VARCHAR(100) NULL, CHANGE `telephone` `telephone` VARCHAR(40) NULL, CHANGE `address` `address` VARCHAR(200)  NULL, CHANGE `identity_number` `identity_number` VARCHAR(40) NULL COMMENT 'ταυτοτητα', CHANGE `rank_date` `rank_date` DATE NULL, CHANGE `pay_scale_date` `pay_scale_date` DATE NULL, CHANGE `service_adoption` `service_adoption` VARCHAR(10) NULL COMMENT 'αναληψη υπηρεσιας', CHANGE `master_degree` `master_degree` TINYINT(3) UNSIGNED NULL DEFAULT '0' COMMENT 'πληθος μεταπτυχιακων τιτλων', CHANGE `doctorate_degree` `doctorate_degree` TINYINT(3) UNSIGNED NULL DEFAULT '0' COMMENT 'πληθος διδακτορικων τιτλων', CHANGE `work_experience` `work_experience` INT(10) UNSIGNED NULL DEFAULT '0' COMMENT 'προυπηρεσια σε ημερες', CHANGE `comments` `comments` LONGTEXT NULL;")
                ->execute();
    }

    public function down()
    {
        return true;
    }

}
