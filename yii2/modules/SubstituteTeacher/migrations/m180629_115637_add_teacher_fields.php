<?php

use yii\db\Migration;
use yii\helpers\Console;

class m180629_115637_add_teacher_fields extends Migration
{
    public function safeUp()
    {
        $registry_table = $this->db->tablePrefix . 'stteacher_registry';
        $yearteacher_table = $this->db->tablePrefix . 'stteacher';
        
        $alter_registry_command = "ALTER TABLE " . $registry_table . " ADD COLUMN `passport_number` VARCHAR(30) UNIQUE DEFAULT NULL COMMENT 'Αριθμός Διαβατηρίου.';";
        Console::stdout("\n*** Adding new column (passport_number) in table " . $registry_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_registry_command . "\n");
        Yii::$app->db->createCommand($alter_registry_command)->execute();
        
        
        $alter_yearteacher_command = "ALTER TABLE " . $yearteacher_table . "
                                      ADD COLUMN `public_experience` SMALLINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Προϋπηρεσία στο Δημόσιο',
                                      ADD COLUMN `smeae_keddy_experience` SMALLINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Προϋπηρεσία στο σε ΣΜΕΑΕ/ΚΕΔΔΥ',
                                      ADD COLUMN `disability_percentage` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Ποσοστό Αναπηρίας',
                                      ADD COLUMN `disabled_children` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Αριθμός Τέκνων με Αναπηρία',
                                      ADD COLUMN `many_children` VARCHAR(15) NOT NULL DEFAULT '' COMMENT 'Πολύτεκνος/Τρίτεκνος';";
        Console::stdout("\n*** Adding new columns (public_experience, smeae_keddy_experience, disability_percentage, disabled_children, many_children)
                                      in table " . $yearteacher_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_yearteacher_command . "\n");
        Yii::$app->db->createCommand($alter_yearteacher_command)->execute();        
    }

    public function safeDown()
    {
        $registry_table = $this->db->tablePrefix . 'stteacher_registry';
        $yearteacher_table = $this->db->tablePrefix . 'stteacher';
        
        $alter_registry_command = "ALTER TABLE " . $registry_table . " DROP COLUMN `passport_number`;";
        Console::stdout("\n*** Dropping column (passport_number) from table " . $registry_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_registry_command . "\n");
        Yii::$app->db->createCommand($alter_registry_command)->execute();
        
        $alter_yearteacher_command = "ALTER TABLE " . $yearteacher_table . " DROP COLUMN `public_experience`,
                                      DROP COLUMN `smeae_keddy_experience`,  DROP COLUMN `disability_percentage`,
                                      DROP COLUMN `disabled_children`,  DROP COLUMN `many_children`;";
        
        Console::stdout("\n*** Dropping columns (public_experience, smeae_keddy_experience, disability_percentage, disabled_children, many_children)
                               from table " . $yearteacher_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_yearteacher_command . "\n");
        Yii::$app->db->createCommand($alter_yearteacher_command)->execute();        
    }
}
