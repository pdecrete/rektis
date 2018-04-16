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
            'table_country' => $this->db->tablePrefix . 'schtransport_country'
        ];
        $i = 1;
        /* CREATE TABLE admapp_trnsprt_programcategory */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbTrnsprtTables['table_programcategory'] .
                          " (`programcategory_id` INTEGER,
                             `programcategory_programtitle` VARCHAR(200) NOT NULL COMMENT 'Τίτλος Δράσης',
                             `programcategory_programdescription` VARCHAR(400) COMMENT 'Περιγραφή Δράσης',
                             `programcategory_programparent` INTEGER,
                              PRIMARY KEY (`programcategory_id`),
                              UNIQUE KEY (`programcategory_programtitle`)
                            ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbTrnsprtTables['table_programcategory'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        $insert_command = "INSERT INTO " . $dbTrnsprtTables['table_programcategory'] . "(programcategory_id, programcategory_programtitle, programcategory_programdescription, programcategory_programparent) VALUES ";
        Yii::$app->db->createCommand($insert_command . "(1, 'Ευρωπαϊκά Προγράμματα και λοιπές ευρωπαϊκές δραστηριότητες', '', NULL)")->execute();
        Yii::$app->db->createCommand($insert_command . "(2, 'Εκπαιδευτικές ανταλλαγές, αδελφοποιήσεις, εκπαιδευτικά προγράμματα, προγράμματα διεθνών οργανισμών και διεθνείς συμμετοχές', '', NULL)")->execute();
        Yii::$app->db->createCommand($insert_command . "(3, 'Σχολείο Ευρωπαϊκής Παιδείας', '', NULL)")->execute();
        Yii::$app->db->createCommand($insert_command . "(4, 'Erasmus+ KA1', 'Μαθησιακή Κινητικότητα Προσωπικού Σχολικής Εκπαίδευσης', 1)")->execute();
        Yii::$app->db->createCommand($insert_command . "(5, 'Erasmus+ KA2', 'Στρατηγικές Συμπράξεις', 1)")->execute();
        Yii::$app->db->createCommand($insert_command . "(6, 'Διδακτικές Επισκέψεις', '', 3)")->execute();
        Yii::$app->db->createCommand($insert_command . "(7, 'Εκπαιδευτικές Επισκέψεις', '', 3)")->execute();
        Yii::$app->db->createCommand($insert_command . "(8, 'Εκπαιδευτικές Εκδρομές', '', 3)")->execute();
        Yii::$app->db->createCommand($insert_command . "(9, 'Σχολικοί Περίπατοι', '', 3)")->execute();
        Yii::$app->db->createCommand($insert_command . "(10, 'Πολυήμερες Εκδρομές στο εξωτερικό', '', NULL)")->execute();
        
        
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
                             `meeting_city` VARCHAR(100) NOT NULL COMMENT 'Πόλη',
                             `meeting_country` VARCHAR(100) NOT NULL COMMENT 'Χώρα',
                             `meeting_startdate` DATE NOT NULL COMMENT 'Έναρξη συνάντησης',
                             `meeting_enddate` DATE NOT NULL COMMENT 'Λήξη συνάντησης',
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
                          " (`directorate_id` INTEGER NOT NULL AUTO_INCREMENT,
                             `directorate_name` VARCHAR(200) NOT NULL,
                             `directorate_shortname` VARCHAR(100),
                              PRIMARY KEY (`directorate_id`),
                              UNIQUE KEY (`directorate_name`),
                              UNIQUE KEY (`directorate_shortname`)
                            ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbTrnsprtTables['table_directorate'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        $insert_command = "INSERT INTO " . $dbTrnsprtTables['table_directorate'] . "(directorate_name, directorate_shortname) VALUES ";
        Yii::$app->db->createCommand($insert_command . "('Περιφερειακή Διεύθυνση Πρωτοβάθμιας και Δευτεροβάθμιας Εκπαίδευσης Κρήτης', 'ΠΔΕ Κρήτης')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Διεύθυνση Πρωτοβάθμιας Εκπαίδευσης Νομού Ηρακλείου', 'ΔΠΕ Ηρακλείου')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Διεύθυνση Δευτεροβάθμιας Εκπαίδευσης Νομού Ηρακλείου', 'ΔΔΕ Ηρακλείου')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Διεύθυνση Πρωτοβάθμιας Εκπαίδευσης Νομού Χανίων', 'ΔΠΕ Χανίων')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Διεύθυνση Δευτεροβάθμιας Εκπαίδευσης Νομού Χανίων', 'ΔΔΕ Χανίων')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Διεύθυνση Πρωτοβάθμιας Εκπαίδευσης Νομού Ρεθύμνου', 'ΔΠΕ Ρεθύμνου')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Διεύθυνση Δευτεροβάθμιας Εκπαίδευσης Νομού Ρεθύμνου', 'ΔΔΕ Ρεθύμνου')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Διεύθυνση Πρωτοβάθμιας Εκπαίδευσης Νομού Λασιθίου', 'ΔΠΕ Λασιθίου')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Διεύθυνση Δευτεροβάθμιας Εκπαίδευσης Νομού Λασιθίου', 'ΔΔΕ Λασιθίου')")->execute();
        
        /* CREATE TABLE admapp_school */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbTrnsprtTables['table_school'] .
                          " (`school_id` INTEGER NOT NULL AUTO_INCREMENT,
                             `school_name` VARCHAR(200) NOT NULL COMMENT 'Σχολείο',
                             `school_mm_id` INTEGER NOT NULL COMMENT 'mm_id of school as set in MySchool',
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
        " (`country_id` INTEGER NOT NULL AUTO AUTO_INCREMENT,
           `country_name` VARCHAR(100) NOT NULL,
           `country_name_genitive` VARHCAR(100)
          )" . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbTrnsprtTables['table_country'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        $insert_command = "INSERT INTO " . $dbTrnsprtTables['table_country'] . "(country_name, country_name_genitive) VALUES ";
        Yii::$app->db->createCommand($insert_command . "('Βέ', '')")->execute();
        
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
            'table_country' => $this->db->tablePrefix . 'schtransport_country'
        ];
        
        $dbTrnsprtTables = array_reverse($dbTrnsprtTables);
        $i = 0;
        foreach ($dbTrnsprtTables as $dbtable) {
            Console::stdout(++$i . ". Dropping table " . $dbtable . ".\n");
            $this->dropTable($dbtable);
        }
    }
    
}
