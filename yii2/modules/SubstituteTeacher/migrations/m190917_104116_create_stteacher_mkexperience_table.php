<?php

use yii\db\Migration;
use yii\helpers\Console;
/**
 * Handles the creation of table `stteacher_mkexperience`.
 */
class m190917_104116_create_stteacher_mkexperience_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $created_table = $this->db->tablePrefix . 'stteacher_mkexperience';
        $create_command = "CREATE TABLE " . $created_table . 
        "(  `id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
            `teacher_id` INT NOT NULL,
            `exp_startdate` DATE NOT NULL,
            `exp_enddate` DATE NOT NULL,
            `exp_hours` TINYINT UNSIGNED NOT NULL DEFAULT 0,
            `exp_hourspweek` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
            `exp_years` TINYINT UNSIGNED DEFAULT 0,
            `exp_months` SMALLINT UNSIGNED DEFAULT 0,
            `exp_days` SMALLINT  UNSIGNED DEFAULT 0,
            `exp_sectorname` VARCHAR(50) NOT NULL,
            `exp_sectortype` TINYINT(1) UNSIGNED  DEFAULT 0,
            `exp_info` varchar(100),
            `exp_mkvalid` TINYINT(1) UNSIGNED  DEFAULT 1
         ) ENGINE=InnoDB DEFAULT CHARSET=utf8;\n".
         " ALTER TABLE " . $created_table .
         " ADD CONSTRAINT `". $created_table . "_bfk_1` FOREIGN KEY (`teacher_id`) REFERENCES ". $this->db->tablePrefix .'stteacher'. " (`id`);";
         
                
        $trigger_command1 = "CREATE TRIGGER before_mkexp_insert BEFORE INSERT ON " . $created_table . " FOR EACH ROW BEGIN\n".
                            "IF (NEW.exp_mkvalid = 1) THEN \n".
                                "UPDATE admapp_stteacher SET \n".
                                "admapp_stteacher.mk_exptotdays = admapp_stteacher.mk_exptotdays + NEW.exp_years*360+NEW.exp_months*30+NEW.exp_days,".
                                "admapp_stteacher.mk_years = admapp_stteacher.mk_exptotdays DIV 360,".
                                "admapp_stteacher.mk_months =(admapp_stteacher.mk_exptotdays MOD 360) DIV 30,".
                                "admapp_stteacher.mk_days = admapp_stteacher.mk_exptotdays MOD 30 WHERE NEW.teacher_id = admapp_stteacher.id;\n".
                                //"admapp_stteacher.mk = 1 + (admapp_stteacher.mk_years + admapp_stteacher.mk_titleyears) DIV admapp_stteacher.mk_yearsper WHERE NEW.teacher_id = admapp_stteacher.id;\n".
                            "END IF;END\n";
         $trigger_command2 ="CREATE TRIGGER before_mkexp_update BEFORE UPDATE ON ".  $created_table . " FOR EACH ROW BEGIN\n".
                            "IF (NEW.exp_mkvalid = 1) THEN\n".
                                "IF (OLD.exp_mkvalid = 1) THEN \n".
                                    "UPDATE admapp_stteacher SET \n".
                                    "admapp_stteacher.mk_exptotdays = admapp_stteacher.mk_exptotdays - (OLD.exp_years*360+OLD.exp_months*30+OLD.exp_days)+ (NEW.exp_years*360+NEW.exp_months*30+NEW.exp_days),".                 
                                    "admapp_stteacher.mk_years = admapp_stteacher.mk_exptotdays DIV 360,\n".
                                    "admapp_stteacher.mk_months = (admapp_stteacher.mk_exptotdays MOD 360) DIV 30,".
                                    "admapp_stteacher.mk_days = admapp_stteacher.mk_exptotdays MOD 30 WHERE NEW.teacher_id = admapp_stteacher.id;\n".
                                    //"admapp_stteacher.mk = 1 + (admapp_stteacher.mk_years + admapp_stteacher.mk_titleyears) DIV admapp_stteacher.mk_yearsper WHERE NEW.teacher_id = admapp_stteacher.id;\n".
                                "ELSE\n".
                                    "UPDATE admapp_stteacher SET \n".
                                    "admapp_stteacher.mk_exptotdays = admapp_stteacher.mk_exptotdays + NEW.exp_years*360+NEW.exp_months*30+NEW.exp_days,".
                                    "admapp_stteacher.mk_years = admapp_stteacher.mk_exptotdays DIV 360,".
                                    "admapp_stteacher.mk_months =(admapp_stteacher.mk_exptotdays MOD 360) DIV 30,".
                                    "admapp_stteacher.mk_days = admapp_stteacher.mk_exptotdays MOD 30 WHERE NEW.teacher_id = admapp_stteacher.id;\n".
                                    //"admapp_stteacher.mk = 1 + (admapp_stteacher.mk_years + admapp_stteacher.mk_titleyears) DIV admapp_stteacher.mk_yearsper WHERE NEW.teacher_id = admapp_stteacher.id;\n".
                                "END IF;\n".	
                            "ELSE\n".
                                "IF (OLD.exp_mkvalid = 1) THEN \n".
                                    "UPDATE admapp_stteacher SET \n".
                                    "admapp_stteacher.mk_exptotdays = admapp_stteacher.mk_exptotdays - (OLD.exp_years*360+OLD.exp_months*30+OLD.exp_days),".                 
                                    "admapp_stteacher.mk_years = admapp_stteacher.mk_exptotdays DIV 360,\n".
                                    "admapp_stteacher.mk_months = (admapp_stteacher.mk_exptotdays MOD 360) DIV 30,".
                                    "admapp_stteacher.mk_days = admapp_stteacher.mk_exptotdays MOD 30 WHERE NEW.teacher_id = admapp_stteacher.id;\n".
                                    //"admapp_stteacher.mk = 1 + (admapp_stteacher.mk_years + admapp_stteacher.mk_titleyears) DIV admapp_stteacher.mk_yearsper WHERE NEW.teacher_id = admapp_stteacher.id;\n".
                                "END IF;\n".
                             "END IF;\nEND\n";
         $trigger_command3 = "CREATE TRIGGER before_mkexp_delete BEFORE DELETE ON ". $created_table . " FOR EACH ROW BEGIN\n".
                             "IF (OLD.exp_mkvalid = 1) THEN \n".
                                "UPDATE admapp_stteacher SET \n".
                                "admapp_stteacher.mk_exptotdays = admapp_stteacher.mk_exptotdays - (OLD.exp_years*360+OLD.exp_months*30+OLD.exp_days),".                 
                                "admapp_stteacher.mk_years = admapp_stteacher.mk_exptotdays DIV 360,\n".
                                "admapp_stteacher.mk_months = (admapp_stteacher.mk_exptotdays MOD 360) DIV 30,".
                                "admapp_stteacher.mk_days = admapp_stteacher.mk_exptotdays MOD 30 WHERE OLD.teacher_id = admapp_stteacher.id;\n".
                                //"admapp_stteacher.mk = 1 + (admapp_stteacher.mk_years + admapp_stteacher.mk_titleyears) DIV admapp_stteacher.mk_yearsper WHERE OLD.teacher_id = admapp_stteacher.id;\n".
                             "END IF;\nEND\n";
                             // ." COMMIT;";
                            
        Console::stdout("\n*** Created table " . $created_table . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        
        Console::stdout("\n*** Triggered table " . $created_table . ". *** \n");
        Console::stdout("SQL Command: " . $trigger_command1."\n".$trigger_command2."\n" . $trigger_command3."\n");
        Yii::$app->db->createCommand($trigger_command1)->execute();        
        Yii::$app->db->createCommand($trigger_command2)->execute();        
        Yii::$app->db->createCommand($trigger_command3)->execute();        
        
        
//            Console::stdout("Migration succeeded\n");
//        } else {
//            Console::stdout("Error applying Migration\n");
//        }
//        $this->createTable('stmkexperience', [
//            'id' => $this->primaryKey(),
//        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $deleted_table = $this->db->tablePrefix . 'stteacher_mkexperience';
        $delete_command = "DROP TABLE " . $deleted_table ."; COMMIT;"; ;
        Console::stdout("\n*** Deleted table " . $deleted_table . ". *** \n");
        Console::stdout("SQL Command: " . $delete_command . "\n");
        Yii::$app->db->createCommand($delete_command)->execute();
//            Console::stdout("Migration reverted\n");
//        } else {
//            Console::stdout("Error reverting Migration\n");
//        }        
    }
}
