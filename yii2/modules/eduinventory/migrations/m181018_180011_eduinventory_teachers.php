<?php

use yii\db\Migration;
use yii\helpers\Console;

class m181018_180011_eduinventory_teachers extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $table_teacher = $this->db->tablePrefix . 'teacher';
        $table_schoolunit = $this->db->tablePrefix . 'schoolunit';
        $table_specialisation = $this->db->tablePrefix . 'specialisation';
        
        /* CREATE TABLE admapp_disposal_teacher */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $table_teacher .
                          " (`teacher_id` INTEGER NOT NULL AUTO_INCREMENT,
                             `teacher_surname` VARCHAR(100) NOT NULL COMMENT 'Επίθετο',
                             `teacher_name` VARCHAR(100) NOT NULL COMMENT 'Όνομα',
                             `teacher_fathername` VARCHAR(100) NOT NULL COMMENT 'Πατρώνυμο',
                             `teacher_mothername` VARCHAR(100) NOT NULL COMMENT 'Μητρώνυμο',
                             `teacher_gender` BOOLEAN NOT NULL COMMENT 'Φύλο',
                             `teacher_registrynumber` VARCHAR(50) NOT NULL COMMENT 'Αριθμός Μητρώου',
                             `specialisation_id` INTEGER NOT NULL,
                             `school_id` INTEGER NOT NULL,
                              PRIMARY KEY (`teacher_id`),
                              FOREIGN KEY (`specialisation_id`) REFERENCES " . $table_specialisation . " (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                              FOREIGN KEY (`school_id`) REFERENCES " . $table_schoolunit . " (`school_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                              UNIQUE KEY (`teacher_registrynumber`)
                            ) " . $tableOptions;
        Console::stdout("\n*** Creating table " . $table_teacher . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
    }
    
    public function safeDown()
    {
        $table_teacher = $this->db->tablePrefix . 'teacher';        
        Console::stdout("Dropping table " . $table_teacher . ".\n");
        $this->dropTable($table_teacher);
    }
}
