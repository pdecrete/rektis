<?php
use yii\helpers\Console;
use yii\db\Migration;

class m161011_045618_employee_mandatory_fields extends Migration
{
 public function safeUp()
    {
        $table_name = $this->db->tablePrefix . 'employee';
        Console::stdout("Altering table '{$table_name}': Allowing NULL values for fields appointment_fek, appointment_date, service_adoption_date, social_security_number.\n");
        Yii::$app->db->createCommand(" Alter table `{$table_name}` CHANGE `appointment_fek` `appointment_fek` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'ΦΕΚ διορισμου' ")
                ->execute();
        Yii::$app->db->createCommand(" Alter table `{$table_name}` CHANGE `appointment_date` `appointment_date` DATE NULL COMMENT 'ημερομηνια διορισμου' ")
                ->execute();
        Yii::$app->db->createCommand(" Alter table `{$table_name}` CHANGE `service_adoption_date` `service_adoption_date` DATE NULL ")
                ->execute();
        Yii::$app->db->createCommand(" Alter table `{$table_name}` CHANGE `social_security_number` `social_security_number` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'ΑΜΚΑ' ")
                ->execute();
        return true;
    }

    public function safeDown()
    {
        $table_name = $this->db->tablePrefix . 'employee';
        Console::stdout("Altering table '{$table_name}': Declaring NOT NULL values for fields appointment_fek, appointment_date, service_adoption_date, social_security_number.\n");
        Yii::$app->db->createCommand(" Alter table `{$table_name}` CHANGE `appointment_fek` `appointment_fek` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'ΦΕΚ διορισμου' ")
                ->execute();
        Yii::$app->db->createCommand(" Alter table `{$table_name}` CHANGE `appointment_date` `appointment_date` DATE NOT NULL COMMENT 'ημερομηνια διορισμου' ")
                ->execute();
        Yii::$app->db->createCommand(" Alter table `{$table_name}` CHANGE `service_adoption_date` `service_adoption_date` DATE NOT NULL ")
                ->execute();
        Yii::$app->db->createCommand(" Alter table `{$table_name}` CHANGE `social_security_number` `social_security_number` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'ΑΜΚΑ' ")
                ->execute();
        return true;
    }
}




