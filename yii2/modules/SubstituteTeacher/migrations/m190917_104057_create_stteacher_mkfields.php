<?php

use yii\db\Migration;
use yii\helpers\Console;
use app\traits\DbMigrates;

class m190917_104057_create_stteacher_mkfields extends Migration
{
    use DbMigrates;
    public function safeUp()
    {
        $this->allowInvalidDates();

        $yearteacher_table = $this->db->tablePrefix . 'stteacher';
        
        $alter_yearteacher_command = "ALTER TABLE " . $yearteacher_table . 
                                    " ADD COLUMN `mk_years` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Συνολικά έτη για ΜΚ',
                                      ADD COLUMN `mk_months` SMALLINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Συνολικοί μήνες για ΜΚ',
                                      ADD COLUMN `mk_days` SMALLINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Συνολικές ημέρες για ΜΚ',
                                      ADD COLUMN `mk_exptotdays` SMALLINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Σύνολο ημερών αναγνωρισμένης προϋπηρεσίας για ΜΚ',
                                      ADD COLUMN `mk_appdate` DATE NULL COMMENT 'Ημερομηνία αίτησης αναγνώρισης προϋπηρεσίας',
                                      ADD COLUMN `mk_titleyears` TINYINT UNSIGNED DEFAULT 0 COMMENT 'Έτη Ανώτερου συναφούς τίτλου',
                                      ADD COLUMN `mk_titleappdate` DATE NULL COMMENT 'Ημερομηνία αίτησης αναγνώρισης τίτλου',
                                      ADD COLUMN `mk_titleinfo` VARCHAR(200) NULL COMMENT 'Παρατηρήσεις αναγνώρισης τίτλου', 
                                      ADD COLUMN `mk_yearsper` TINYINT(1) UNSIGNED DEFAULT 2 COMMENT 'Έτη ανά ΜΚ',
                                      ADD COLUMN `mk` TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Υπολογιζόμενο ΜΚ',
                                      ADD COLUMN `mk_changedate` DATE NULL COMMENT 'Εν δυνάμει ημ/νία αλλαγής ΜΚ',
                                      ADD COLUMN `operation_descr` VARCHAR(40) NULL COMMENT 'Πράξη',
                                      ADD COLUMN `sector` TINYINT UNSIGNED COMMENT 'Φορέας τοποθέτησης'";        
            
        Console::stdout("\n*** Adding new columns (mk_years, mk_months, mk_days, mk_exptotdays mk_appdate, mk_title, mk_titleappdate) in table " . $yearteacher_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_yearteacher_command . "\n");
        Yii::$app->db->createCommand($alter_yearteacher_command)->execute();                 
    }

    public function safeDown()
    {
        
        $this->allowInvalidDates();

        $yearteacher_table = $this->db->tablePrefix . 'stteacher';
             
        $alter_yearteacher_command = "ALTER TABLE " . $yearteacher_table . 
                                    " DROP COLUMN `mk_years`, DROP COLUMN `mk_months`,  DROP COLUMN `mk_days`, 
                                      DROP COLUMN `mk_exptotdays`, DROP COLUMN `mk_appdate`,  
                                      DROP COLUMN `mk_titleyears`, DROP COLUMN `mk_titleappdate`, DROP COLUMN `mk_titleinfo`,
                                      DROP COLUMN `mk_yearsper`, DROP COLUMN `mk`, DROP COLUMN `mk_changedate`, 
                                      DROP COLUMN `operation_descr`, DROP COLUMN `sector`";
 
        Console::stdout("\n*** Dropping columns (mk_years, mk_months, mk_days, mk_appdate, mk_title, mk_titleappdate, mk)
                               from table " . $yearteacher_table . ". *** \n");
        Console::stdout("SQL Command: " . $alter_yearteacher_command . "\n");
        Yii::$app->db->createCommand($alter_yearteacher_command)->execute();        
        
    }
}
