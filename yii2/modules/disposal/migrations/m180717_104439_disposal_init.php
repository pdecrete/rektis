<?php

use yii\db\Migration;
use yii\helpers\Console;

class m180717_104439_disposal_init extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $dbDisposalTables = [
            'table_teacher' => $this->db->tablePrefix . 'teacher',
            'table_disposalreason' => $this->db->tablePrefix . 'disposal_disposalreason',
            'table_disposalworkobj' => $this->db->tablePrefix . 'disposal_disposalworkobj',
            'table_localdirdecision' => $this->db->tablePrefix . 'disposal_localdirdecision',
            'table_disposal' => $this->db->tablePrefix . 'disposal_disposal',
            'table_ledger' => $this->db->tablePrefix . 'disposal_ledger',
            'table_approval' => $this->db->tablePrefix . 'disposal_approval',
            'table_disposalapproval' => $this->db->tablePrefix . 'disposal_disposalapproval',            
        ];
        $table_schoolunit = $this->db->tablePrefix . 'schoolunit';
        $table_specialisation = $this->db->tablePrefix . 'specialisation';
        $table_user = $this->db->tablePrefix . 'user';
        $i = 1;

        /* CREATE TABLE admapp_disposal_teacher */
        /*$create_command = "CREATE TABLE IF NOT EXISTS " . $dbDisposalTables['table_teacher'] .
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
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbDisposalTables['table_teacher'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        */
        
        /* CREATE TABLE admapp_disposal_disposalreason */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbDisposalTables['table_disposalreason'] .
                          " (`disposalreason_id` INTEGER NOT NULL AUTO_INCREMENT,
                             `disposalreason_name` VARCHAR(50) NOT NULL COMMENT 'Λεκτικό Αναγνωριστικό',
                             `disposalreason_description` VARCHAR(200) NOT NULL COMMENT 'Λόγος Διάθεσης',
                             PRIMARY KEY (`disposalreason_id`),
                             UNIQUE KEY (`disposalreason_name`)
                            ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbDisposalTables['table_disposalreason'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();        
        $insert_command = "INSERT INTO " . $dbDisposalTables['table_disposalreason'] . "(disposalreason_name, disposalreason_description) VALUES ";
        Yii::$app->db->createCommand($insert_command . "('supplementing_workinghours', 'Συμπλήρωση ωραρίου')")->execute();
        Yii::$app->db->createCommand($insert_command . "('cover_timeoff', 'Κάλυψη ολιγοήμερης άδειας')")->execute();
        Yii::$app->db->createCommand($insert_command . "('health_reasons', 'Λόγους υγείας')")->execute();
        Yii::$app->db->createCommand($insert_command . "('service_reasons', 'Υπηρεσιακούς λόγους')")->execute();

        /* CREATE TABLE admapp_disposal_disposalworkobj */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbDisposalTables['table_disposalworkobj'] .
                          " (`disposalworkobj_id` INTEGER NOT NULL AUTO_INCREMENT,
                             `disposalworkobj_name` VARCHAR(50) NOT NULL COMMENT 'Αντικείμενο Εργασίας Διάθεσης',
                             `disposalworkobj_description` VARCHAR(200) NOT NULL COMMENT 'Αντικείμενο Εργασίας Διάθεσης',
                             PRIMARY KEY (`disposalworkobj_id`),
                             UNIQUE KEY (`disposalworkobj_name`)
                            ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbDisposalTables['table_disposalworkobj'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        $insert_command = "INSERT INTO " . $dbDisposalTables['table_disposalworkobj'] . "(disposalworkobj_name, disposalworkobj_description) VALUES ";
        Yii::$app->db->createCommand($insert_command . "('administrative_work', 'Παροχή διοικητικού έργου')")->execute();
        Yii::$app->db->createCommand($insert_command . "('secretary_work', 'Γραμματειακή υποστήριξη')")->execute();
        Yii::$app->db->createCommand($insert_command . "('supplementary_teaching', 'Ενισχυτική διδασκαλία')")->execute();
        
        
        /* CREATE TABLE admapp_disposal_localdirdecision */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbDisposalTables['table_localdirdecision'] .
                          " (`localdirdecision_id` INTEGER NOT NULL AUTO_INCREMENT, 
                             `localdirdecision_protocol` VARCHAR(100) NOT NULL COMMENT 'Πρωτόκολλο Διεύθυνσης Σχολείου',
                             `localdirdecision_subject` VARCHAR(500) NOT NULL COMMENT 'Θέμα Απόφασης Διεύθυνσης Σχολείου',
                             `localdirdecision_action` VARCHAR(200) NOT NULL COMMENT 'Πράξη Απόφασης Διεύθυνσης Σχολείου',
                             `created_at` TIMESTAMP COMMENT 'Ημ/νία Δημιουργίας',
                             `updated_at` TIMESTAMP COMMENT 'Ημ/νία Επεξεργασίας',
                             `created_by` INTEGER,
                             `updated_by` INTEGER,
                             `deleted` BOOLEAN NOT NULL DEFAULT 0,
                             `archived` BOOLEAN NOT NULL DEFAULT 0,
                             `directorate_id` INTEGER NOT NULL COMMENT 'Διεύθυνση Εκπαίδευσης Έκδοσης Απόφασης',
                              PRIMARY KEY (`localdirdecision_id`),
                              FOREIGN KEY (`created_by`) REFERENCES " . $table_user . " (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                              FOREIGN KEY (`updated_by`) REFERENCES " . $table_user . " (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                              FOREIGN KEY (`directorate_id`) REFERENCES " . $this->db->tablePrefix . 'directorate' . "(`directorate_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                              UNIQUE KEY (`localdirdecision_protocol`, `directorate_id`)
                            ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbDisposalTables['table_localdirdecision'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        
        /* CREATE TABLE admapp_disposal_disposal */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbDisposalTables['table_disposal'] .
                          " (`disposal_id` INTEGER NOT NULL AUTO_INCREMENT,
                             `disposal_startdate` DATE NOT NULL COMMENT 'Έναρξη Διάθεσης',
                             `disposal_enddate` DATE DEFAULT NULL COMMENT 'Λήξη Διάθεσης',
                             `disposal_hours` TINYINT NOT NULL COMMENT 'Ώρες Διάθεσης',
                             `disposal_republished` INTEGER DEFAULT NULL,
                             `disposal_rejected` INTEGER DEFAULT 0,
                             `created_at` TIMESTAMP COMMENT 'Ημ/νία Δημιουργίας',
                             `updated_at` TIMESTAMP COMMENT 'Ημ/νία Επεξεργασίας',
                             `created_by` INTEGER,
                             `updated_by` INTEGER,
                             `deleted` BOOLEAN NOT NULL DEFAULT 0,
                             `archived` BOOLEAN NOT NULL DEFAULT 0,
                             `teacher_id` INTEGER NOT NULL,
                             `school_id` INTEGER NOT NULL,
                             `disposalreason_id` INTEGER NOT NULL,
                             `disposalworkobj_id` INTEGER,
                             `localdirdecision_id` INTEGER NOT NULL,
                              PRIMARY KEY (`disposal_id`),
                              FOREIGN KEY (`created_by`) REFERENCES " . $table_user . " (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                              FOREIGN KEY (`updated_by`) REFERENCES " . $table_user . " (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                              FOREIGN KEY (`teacher_id`) REFERENCES " . $dbDisposalTables['table_teacher'] . " (`teacher_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                              FOREIGN KEY (`school_id`) REFERENCES " . $table_schoolunit . " (`school_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                              FOREIGN KEY (`disposalreason_id`) REFERENCES " . $dbDisposalTables['table_disposalreason'] . " (`disposalreason_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                              FOREIGN KEY (`disposalworkobj_id`) REFERENCES " . $dbDisposalTables['table_disposalworkobj'] . " (`disposalworkobj_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                              FOREIGN KEY (`localdirdecision_id`) REFERENCES " . $dbDisposalTables['table_localdirdecision'] . " (`localdirdecision_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . "
                            ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbDisposalTables['table_disposal'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        
        /* CREATE TABLE admapp_disposal_ledger */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbDisposalTables['table_ledger'] .
                            "(`ledger_id` INTEGER NOT NULL AUTO_INCREMENT,
                              `disposal_id` INTEGER NOT NULL,
                              `disposal_startdate` DATE NOT NULL COMMENT 'Έναρξη Διάθεσης',
                              `disposal_enddate` DATE NOT NULL COMMENT 'Λήξη Διάθεσης',
                              `disposal_hours` TINYINT NOT NULL COMMENT 'Ώρες Διάθεσης',
                              `disposal_action` VARCHAR(200) NOT NULL COMMENT 'Πράξη Διάθεσης',
                              `created_at` TIMESTAMP COMMENT 'Ημ/νία Δημιουργίας',                              
                              `updated_at` TIMESTAMP COMMENT 'Ημ/νία Επεξεργασίας',
                              `deleted` BOOLEAN NOT NULL DEFAULT 0,
                              `created_by` INTEGER NOT NULL,
                              `updated_by` INTEGER NOT NULL,
                              `teacher_id` INTEGER NOT NULL,
                              `school_id` INTEGER NOT NULL,
                               PRIMARY KEY (`ledger_id`),
                               FOREIGN KEY (`created_by`) REFERENCES " . $table_user . " (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                               FOREIGN KEY (`updated_by`) REFERENCES " . $table_user . " (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                               FOREIGN KEY (`teacher_id`) REFERENCES " . $dbDisposalTables['table_teacher'] . " (`teacher_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                               FOREIGN KEY (`school_id`) REFERENCES " . $table_schoolunit . " (`school_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . "
                             ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbDisposalTables['table_ledger'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();

        /* CREATE TABLE admapp_disposal_approvaltype */
        /*$create_command = "CREATE TABLE IF NOT EXISTS " . $dbDisposalTables['table_approvaltype'] .
                          " (`approvaltype_id` INTEGER NOT NULL AUTO_INCREMENT,
                             `approvaltype_name` VARCHAR(50) NOT NULL COMMENT 'Τύπος Απόφασης Διάθεσης',
                             `approvaltype_description` VARCHAR(200) NOT NULL COMMENT 'Τύπος Απόφασης Διάθεσης',
                              PRIMARY KEY (`approvaltype_id`)
                            ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbDisposalTables['table_approvaltype'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        */
        
        /* CREATE TABLE admapp_disposal_approval */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbDisposalTables['table_approval'] .
                          " (`approval_id` INTEGER NOT NULL AUTO_INCREMENT,
                             `approval_regionaldirectprotocol` VARCHAR(100) NOT NULL COMMENT 'Πρωτόκολλο ΠΔΕ',
                             `approval_regionaldirectprotocoldate` DATE NOT NULL COMMENT 'Ημερομηνία Πρωτοκόλλου ΠΔΕ',
                             `approval_notes` VARCHAR(500) COMMENT 'Σημειώσεις',
                             `approval_file` VARCHAR(300) NOT NULL COMMENT 'Αρχείο Έγκρισης',
                             `approval_signedfile` VARCHAR(300) COMMENT 'Ψηφιακά Υπογεγραμμένο Αρχείο Έγκρισης',
                             `approval_republished` INTEGER DEFAULT NULL,
                             `deleted` BOOLEAN NOT NULL DEFAULT 0,
                             `archived` BOOLEAN NOT NULL DEFAULT 0,
                             `created_at` TIMESTAMP COMMENT 'Ημ/νία Δημιουργίας', 
                             `updated_at` TIMESTAMP COMMENT 'Ημ/νία Επεξεργασίας', 
                             `created_by` INTEGER NOT NULL,
                             `updated_by` INTEGER NOT NULL,
                              PRIMARY KEY (`approval_id`),
                              FOREIGN KEY (`created_by`) REFERENCES " . $table_user . " (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                              FOREIGN KEY (`updated_by`) REFERENCES " . $table_user . " (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . "
                            ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbDisposalTables['table_approval'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        
        
        /* CREATE TABLE admapp_disposal_disposalapproval */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbDisposalTables['table_disposalapproval'] .
                          " (`disposal_id` INTEGER NOT NULL,
                             `approval_id` INTEGER NOT NULL,
                              PRIMARY KEY (`disposal_id`, `approval_id`),
                              FOREIGN KEY (`disposal_id`) REFERENCES " . $dbDisposalTables['table_disposal'] . " (`disposal_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                              FOREIGN KEY (`approval_id`) REFERENCES " . $dbDisposalTables['table_approval'] . " (`approval_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . "
                            ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbDisposalTables['table_disposalapproval'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
    }

    public function safeDown()
    {
        $dbDisposalTables = [
            //'table_teacher' => $this->db->tablePrefix . 'teacher',
            'table_disposalreason' => $this->db->tablePrefix . 'disposal_disposalreason',
            'table_disposalworkobj' => $this->db->tablePrefix . 'disposal_disposalworkobj',
            'table_localdirdecision' => $this->db->tablePrefix . 'disposal_localdirdecision',
            'table_disposal' => $this->db->tablePrefix . 'disposal_disposal',
            'table_ledger' => $this->db->tablePrefix . 'disposal_ledger',
            'table_approval' => $this->db->tablePrefix . 'disposal_approval',
            'table_disposalapproval' => $this->db->tablePrefix . 'disposal_disposalapproval',            
        ];
        
        $dbDisposalTables = array_reverse($dbDisposalTables);
        $i = 0;
        foreach ($dbDisposalTables as $dbtable) {
            Console::stdout(++$i . ". Dropping table " . $dbtable . ".\n");
            $this->dropTable($dbtable);
        }
    }
}