<?php

use yii\db\Migration;
use yii\helpers\Console;

class m180403_094056_schooltransport_init extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $dbTrnsprtTables = [
            'table_programcategory' => $this->db->tablePrefix . 'schtransport_programcategory',
            'table_program' => $this->db->tablePrefix . 'schtransport_program',
            'table_meeting' => $this->db->tablePrefix . 'schtransport_meeting',
            'table_directorate' => $this->db->tablePrefix . 'directorate',
            'table_school' => $this->db->tablePrefix . 'schoolunit',
            'table_transport' => $this->db->tablePrefix . 'schtransport_transport',
            'table_country' => $this->db->tablePrefix . 'schtransport_country',
            'table_state' => $this->db->tablePrefix . 'schtransport_state',
            'table_transportstate' => $this->db->tablePrefix . 'schtransport_transportstate'
        ];
        $i = 1;
        /* CREATE TABLE admapp_trnsprt_programcategory */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbTrnsprtTables['table_programcategory'] .
                          " (`programcategory_id` INTEGER NOT NULL AUTO_INCREMENT,
                             `programcategory_programalias` VARCHAR(50) NOT NULL,
                             `programcategory_programtitle` VARCHAR(200) NOT NULL COMMENT 'Τίτλος Δράσης',
                             `programcategory_programdescription` VARCHAR(400) COMMENT 'Περιγραφή Δράσης',
                             `programcategory_programparent` VARCHAR(50),
                              PRIMARY KEY (`programcategory_id`),
                              UNIQUE KEY (`programcategory_programtitle`),
                              UNIQUE KEY (`programcategory_programalias`)
                            ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbTrnsprtTables['table_programcategory'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        $insert_command = "INSERT INTO " . $dbTrnsprtTables['table_programcategory'] . "(programcategory_programalias, programcategory_programtitle, programcategory_programdescription, programcategory_programparent) VALUES ";
        Yii::$app->db->createCommand($insert_command . "('EUROPEAN', 'Ευρωπαϊκά Προγράμματα και λοιπές ευρωπαϊκές δραστηριότητες', '', NULL)")->execute();
        Yii::$app->db->createCommand($insert_command . "('INTERNATIONAL', 'Εκπαιδευτικές ανταλλαγές, αδελφοποιήσεις, εκπαιδευτικά προγράμματα, προγράμματα διεθνών οργανισμών και διεθνείς συμμετοχές', '', NULL)")->execute();
        Yii::$app->db->createCommand($insert_command . "('EUROPEAN_SCHOOL', 'Σχολείο Ευρωπαϊκής Παιδείας', '', NULL)")->execute();
        Yii::$app->db->createCommand($insert_command . "('KA1', 'Erasmus+ KA1', 'Μαθησιακή Κινητικότητα Προσωπικού Σχολικής Εκπαίδευσης', 'EUROPEAN')")->execute();
        Yii::$app->db->createCommand($insert_command . "('KA1_STUDENTS', 'Erasmus+ KA1 με συμμετοχή μαθητών', 'Μαθησιακή Κινητικότητα Προσωπικού Σχολικής Εκπαίδευσης', 'EUROPEAN')")->execute();
        Yii::$app->db->createCommand($insert_command . "('KA2', 'Erasmus+ KA2', 'Στρατηγικές Συμπράξεις', 'EUROPEAN')")->execute();
        Yii::$app->db->createCommand($insert_command . "('KA2_STUDENTS', 'Erasmus+ KA2 με συμμετοχή μαθητών', 'Στρατηγικές Συμπράξεις', 'EUROPEAN')")->execute();
        Yii::$app->db->createCommand($insert_command . "('TEACHING_VISITS', 'Διδακτική Επίσκεψη', '', 'EUROPEAN_SCHOOL')")->execute();
        Yii::$app->db->createCommand($insert_command . "('EDUCATIONAL_VISITS', 'Εκπαιδευτική Επίσκεψη', '', 'EUROPEAN_SCHOOL')")->execute();
        Yii::$app->db->createCommand($insert_command . "('EDUCATIONAL_EXCURSIONS', 'Εκπαιδευτική Εκδρομή', '', 'EUROPEAN_SCHOOL')")->execute();
        Yii::$app->db->createCommand($insert_command . "('SCHOOL_EXCURIONS', 'Σχολικός Περίπατος', '', 'EUROPEAN_SCHOOL')")->execute();
        Yii::$app->db->createCommand($insert_command . "('EXCURIONS_FOREIGN_COUNTRY', 'Πολυήμερη Εκδρομή στο εξωτερικό', '', NULL)")->execute();
        Yii::$app->db->createCommand($insert_command . "('OMOGENEIA_FOREIGN_COUNTRY', 'Συνεργασία μετά από πρόσκληση σχολείου της Ομογένειας', '', 'INTERNATIONAL')")->execute();
        Yii::$app->db->createCommand($insert_command . "('ETWINNING_FOREIGN_COUNTRY', 'Πρόγραμμα eTwinning', '', 'INTERNATIONAL')")->execute();
        Yii::$app->db->createCommand($insert_command . "('SCH_TWINNING_FOREIGN_COUNTRY', 'Αδελφοποίηση με σχολείο εξωτερικού', '', 'INTERNATIONAL')")->execute();
        Yii::$app->db->createCommand($insert_command . "('PARLIAMENT', 'Βουλή των Ελλήνων', '', NULL)")->execute();
        
        
        
        /* CREATE TABLE admapp_trnsprt_program */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbTrnsprtTables['table_program'] .
                          " (`program_id` INTEGER NOT NULL AUTO_INCREMENT,
                             `program_title` VARCHAR(300) NOT NULL COMMENT 'Τίτλος Προγράμματος',
                             `program_code` VARCHAR(100) COMMENT 'Κωδικός Προγράμματος',
                             `programcategory_id` INTEGER NOT NULL,
                              FOREIGN KEY (`programcategory_id`) REFERENCES " . $dbTrnsprtTables['table_programcategory'] . "(`programcategory_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                              PRIMARY KEY (`program_id`),
                              UNIQUE KEY (`program_code`)
                            ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbTrnsprtTables['table_programcategory'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        
        
        /* CREATE TABLE admapp_trnsprt_meeting */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbTrnsprtTables['table_meeting'] .
                          " (`meeting_id` INTEGER NOT NULL AUTO_INCREMENT,
                             `meeting_country` VARCHAR(100) NOT NULL COMMENT 'Χώρα',
                             `meeting_city` VARCHAR(100) NOT NULL COMMENT 'Πόλη',
                             `meeting_hostschool` VARCHAR(200) COMMENT 'Σχολείο Υποδοχής',
                             `meeting_startdate` DATE COMMENT 'Έναρξη συνάντησης',
                             `meeting_enddate` DATE COMMENT 'Λήξη συνάντησης',
                             `program_id` INTEGER NOT NULL,
                              FOREIGN KEY (`program_id`) REFERENCES " . $dbTrnsprtTables['table_program'] . "(`program_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                              PRIMARY KEY (`meeting_id`)
                            ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbTrnsprtTables['table_meeting'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        
        
        /* CREATE TABLE admapp_directorate */
        /* TODO prefecture_id*/
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbTrnsprtTables['table_directorate'] .
                          " (`directorate_id` INTEGER NOT NULL,
                             `directorate_name` VARCHAR(200) NOT NULL,
                             `directorate_shortname` VARCHAR(100),
                             `directorate_stage` VARCHAR(20),
                              PRIMARY KEY (`directorate_id`),
                              UNIQUE KEY (`directorate_name`),
                              UNIQUE KEY (`directorate_shortname`)
                            ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbTrnsprtTables['table_directorate'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        $insert_command = "INSERT INTO " . $dbTrnsprtTables['table_directorate'] . "(directorate_id, directorate_name, directorate_shortname, directorate_stage) VALUES ";
        Yii::$app->db->createCommand($insert_command . "(53, 'Περιφερειακή Διεύθυνση Πρωτοβάθμιας και Δευτεροβάθμιας Εκπαίδευσης Κρήτης', 'ΠΔΕ Κρήτης', NULL)")->execute();
        Yii::$app->db->createCommand($insert_command . "(41, 'Διεύθυνση Πρωτοβάθμιας Εκπαίδευσης Νομού Ηρακλείου', 'ΔΠΕ Ηρακλείου', 'PRIMARY')")->execute();
        Yii::$app->db->createCommand($insert_command . "(15, 'Διεύθυνση Δευτεροβάθμιας Εκπαίδευσης Νομού Ηρακλείου', 'ΔΔΕ Ηρακλείου', 'SECONDARY')")->execute();
        Yii::$app->db->createCommand($insert_command . "(60, 'Διεύθυνση Πρωτοβάθμιας Εκπαίδευσης Νομού Χανίων', 'ΔΠΕ Χανίων', 'PRIMARY')")->execute();
        Yii::$app->db->createCommand($insert_command . "(25, 'Διεύθυνση Δευτεροβάθμιας Εκπαίδευσης Νομού Χανίων', 'ΔΔΕ Χανίων', 'SECONDARY')")->execute();
        Yii::$app->db->createCommand($insert_command . "(75, 'Διεύθυνση Πρωτοβάθμιας Εκπαίδευσης Νομού Ρεθύμνου', 'ΔΠΕ Ρεθύμνου', 'PRIMARY')")->execute();
        Yii::$app->db->createCommand($insert_command . "(100, 'Διεύθυνση Δευτεροβάθμιας Εκπαίδευσης Νομού Ρεθύμνου', 'ΔΔΕ Ρεθύμνου', 'SECONDARY')")->execute();
        Yii::$app->db->createCommand($insert_command . "(72, 'Διεύθυνση Πρωτοβάθμιας Εκπαίδευσης Νομού Λασιθίου', 'ΔΠΕ Λασιθίου', 'PRIMARY')")->execute();
        Yii::$app->db->createCommand($insert_command . "(95, 'Διεύθυνση Δευτεροβάθμιας Εκπαίδευσης Νομού Λασιθίου', 'ΔΔΕ Λασιθίου', 'SECONDARY')")->execute();
        
        /* CREATE TABLE admapp_school 
         * `school_mm_id` INTEGER NOT NULL COMMENT 'mm_id of school as set in MySchool',
         * */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbTrnsprtTables['table_school'] .
                          " (`school_id` INTEGER NOT NULL COMMENT 'mm_id of school as set in MySchool',
                             `school_name` VARCHAR(200) NOT NULL COMMENT 'Σχολείο',                             
                             `directorate_id` INTEGER NOT NULL COMMENT 'Διεύθυνση Εκπαίδευσης Σχολείου',
                              FOREIGN KEY (`directorate_id`) REFERENCES " . $dbTrnsprtTables['table_directorate'] . "(`directorate_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                              PRIMARY KEY (`school_id`),
                              UNIQUE KEY (`school_name`, `directorate_id`)
                            ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbTrnsprtTables['table_school'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        

        /* CREATE TABLE admapp_trnsprt_transport */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbTrnsprtTables['table_transport'] .
                          " (`transport_id` INTEGER NOT NULL AUTO_INCREMENT,
                             `transport_submissiondate` DATE COMMENT 'Ημερομηνία Αίτησης Έγκρισης',
                             `transport_startdate` DATE NOT NULL COMMENT 'Έναρξη Μετακίνησης',
                             `transport_enddate` DATE NOT NULL COMMENT 'Λήξη Μετακίνησης',
                             `transport_headteacher` VARCHAR(100) COMMENT 'Αρχηγός Συνοδός',
                             `transport_teachers` VARCHAR(1000) NOT NULL COMMENT 'Μετακινούμενοι/Συνοδοί Εκπαιδευτικοί',
                             `transport_substituteteachers` VARCHAR(1000) COMMENT 'Αναπληρωτές Συνοδοί Εκπαιδευτικοί',
                             `transport_students` VARCHAR(2000) COMMENT 'Μετακινούμενοι Μαθητές',
                             `transport_class` VARCHAR(10) COMMENT 'Τμήμα Σχολείου',
                             `transport_schoolrecord` VARCHAR(200) COMMENT 'Πρακτικό Συλλόγου',
                             `transport_localdirectorate_protocol` VARCHAR(100) NOT NULL COMMENT 'Πρωτόκολλο Διαβιβαστικού Δ/νσης Σχολείου',
                             `transport_pde_protocol` VARCHAR(100) COMMENT 'Πρωτόκολλο Έγκρισης',
                             `transport_remarks` VARCHAR(500) COMMENT 'Παρατηρήσεις',
                             `transport_datesentapproval` DATE COMMENT 'Ημερομηνία Αποστολής της Έγκρισης Μετακίνησης',
                             `transport_dateprotocolcompleted` VARCHAR(100) COMMENT 'Ημερομηνία Ξεχρέωσης στο Πρωτόκολλο',
                             `transport_approvalfile` VARCHAR(200) COMMENT 'Αρχείο Έγκρισης',
                             `transport_signedapprovalfile` VARCHAR(200) COMMENT 'Αρχείο Ψηφιακά Υπογεγραμμένο',
                             `transport_isarchived` BOOLEAN NOT NULL DEFAULT 0 COMMENT 'Αρχειοθετημένη μετακίνηση',
                             `meeting_id` INTEGER NOT NULL,
                             `school_id` INTEGER NOT NULL,
                              FOREIGN KEY (`meeting_id`) REFERENCES " . $dbTrnsprtTables['table_meeting'] . "(`meeting_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                              FOREIGN KEY (`school_id`) REFERENCES " . $dbTrnsprtTables['table_school'] . "(`school_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                              PRIMARY KEY (`transport_id`)
                            ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbTrnsprtTables['table_transport'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        

        /* CREATE TABLE admapp_country */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbTrnsprtTables['table_country'] .
                          " (`country_id` INTEGER NOT NULL AUTO_INCREMENT,
                             `country_name` VARCHAR(100) NOT NULL,
                              PRIMARY KEY (`country_id`),
                              UNIQUE KEY (`country_name`)
                            )" . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbTrnsprtTables['table_country'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        $insert_command = "INSERT INTO " . $dbTrnsprtTables['table_country'] . " (country_name) VALUES ";
        Yii::$app->db->createCommand($insert_command . "('Βέλγιο')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Ελλάδα')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Λιθουανία')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Πορτογαλία')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Βουλγαρία')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Ισπανία')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Λουξεμβούργο')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Ρουμανία')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Τσεχία')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Γαλλία')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Ουγγαρία')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Σλοβενία')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Δανία')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Κροατία')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Μάλτα')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Σλοβακία')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Γερμανία')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Ιταλία')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Ολλανδία')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Φινλανδία')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Εσθονία')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Κύπρος')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Αυστρία')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Σουηδία')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Ιρλανδία')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Λετονία')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Πολωνία')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Βρετανία')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Αγγλία')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Σκωτία')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Βόρεια Ιρλανδία')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Ουαλία')")->execute();
        Yii::$app->db->createCommand($insert_command . "('ΠΓΔΜ')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Ισλανδία')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Νορβηγία')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Λιχτενστάιν')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Τουρκία')")->execute();
       
        /* CREATE TABLE admapp_schtrnsport_state */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbTrnsprtTables['table_state'] .
                          "(`state_id` INTEGER NOT NULL AUTO_INCREMENT,
                            `state_name` VARCHAR(100),
                             PRIMARY KEY (`state_id`),
                             UNIQUE KEY (`state_name`)
                           ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbTrnsprtTables['table_state'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        $insert_command = "INSERT INTO " . $dbTrnsprtTables['table_state'] . "(state_name) VALUES ";
        Yii::$app->db->createCommand($insert_command . "('Υπογραφή Σχεδίου')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Διεκπεραιώθηκε')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Πρωτοκολλήθηκε/Ξεχρεώθηκε')")->execute();
        
        
        /* CREATE TABLE admapp_schtrnsport_transportstate */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbTrnsprtTables['table_transportstate'] .
                          "(`transport_id` INTEGER NOT NULL,
                            `state_id` INTEGER NOT NULL,
                            `transportstate_date` DATE NOT NULL,
                            `transportstate_comment` VARCHAR(200),
                             PRIMARY KEY (`transport_id`, `state_id`),
                             FOREIGN KEY (`transport_id`) REFERENCES " . $dbTrnsprtTables['table_transport'] . "(`transport_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                             FOREIGN KEY (`state_id`) REFERENCES " . $dbTrnsprtTables['table_state'] . "(`state_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . "
                           ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbTrnsprtTables['table_transportstate'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
    }
    
    public function safeDown()
    {
        $dbTrnsprtTables = [
            'table_programcategory' => $this->db->tablePrefix . 'schtransport_programcategory',
            'table_program' => $this->db->tablePrefix . 'schtransport_program',
            'table_meeting' => $this->db->tablePrefix . 'schtransport_meeting',
            'table_directorate' => $this->db->tablePrefix . 'directorate',
            'table_school' => $this->db->tablePrefix . 'schoolunit',
            'table_transport' => $this->db->tablePrefix . 'schtransport_transport',
            'table_country' => $this->db->tablePrefix . 'schtransport_country',
            'table_state' => $this->db->tablePrefix . 'schtransport_state',
            'table_transportstate' => $this->db->tablePrefix . 'schtransport_transportstate'
        ];
        
        $dbTrnsprtTables = array_reverse($dbTrnsprtTables);
        $i = 0;
        foreach ($dbTrnsprtTables as $dbtable) {
            Console::stdout(++$i . ". Dropping table " . $dbtable . ".\n");
            $this->dropTable($dbtable);
        }
    }
    
}
