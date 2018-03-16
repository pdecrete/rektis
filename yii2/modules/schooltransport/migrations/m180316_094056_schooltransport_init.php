<?php

use yii\db\Migration;
use yii\helpers\Console;

class m180316_094056_schooltransport_init extends Migration
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
        ];
        $i = 1;
        /* CREATE TABLE admapp_trnsprt_programcategory */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbTrnsprtTables['table_programcategory'] .
                          " (`programcategory_id` INTEGER NOT NULL AUTO_INCREMENT,
                             `programcategory_actioncode` VARCHAR(50) NOT NULL COMMENT 'Κωδικός Δράσης',
                             `programcategory_actiontitle` VARCHAR(200) NOT NULL COMMENT 'Τίτλος Δράσης',
                             `programcategory_actionsubcateg` VARCHAR(200) NOT NULL COMMENT 'Κατηγορία Δράσης',
                              PRIMARY KEY (`programcategory_id`)
                            ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbTrnsprtTables['table_programcategory'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();

        
        /* CREATE TABLE admapp_trnsprt_program */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbTrnsprtTables['table_program'] .
                          " (`program_id` INTEGER NOT NULL AUTO_INCREMENT,
                             `program_code` VARCHAR(100) NOT NULL COMMENT 'Κωδικός Προγράμματος',
                             `program_title` VARCHAR(300) NOT NULL COMMENT 'Τίτλος Προγράμματος',
                             `programcategory_id` INTEGER NOT NULL,
                              FOREIGN KEY (`programcategory_id`) REFERENCES " . $dbTrnsprtTables['table_programcategory'] . "(`programcategory_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                              PRIMARY KEY (`program_id`)
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
                              PRIMARY KEY (`directorate_id`)
                            ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbTrnsprtTables['table_directorate'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        $insert_command = "INSERT INTO " . $dbTrnsprtTables['table_directorate'] . "(directorate_name) VALUES ";
        Yii::$app->db->createCommand($insert_command . "('Διεύθυνση Πρωτοβάθμιας Εκπαίδευσης Νομού Ηρακλείου')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Διεύθυνση Δευτεροβάθμιας Εκπαίδευσης Νομού Ηρακλείου')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Διεύθυνση Πρωτοβάθμιας Εκπαίδευσης Νομού Χανίων')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Διεύθυνση Δευτεροβάθμιας Εκπαίδευσης Νομού Χανίων')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Διεύθυνση Πρωτοβάθμιας Εκπαίδευσης Νομού Ρεθύμνου')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Διεύθυνση Δευτεροβάθμιας Εκπαίδευσης Νομού Ρεθύμνου')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Διεύθυνση Πρωτοβάθμιας Εκπαίδευσης Νομού Λασιθίου')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Διεύθυνση Δευτεροβάθμιας Εκπαίδευσης Νομού Λασιθίου')")->execute();
        
        /* CREATE TABLE admapp_school */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbTrnsprtTables['table_school'] .
                          " (`school_id` INTEGER NOT NULL AUTO_INCREMENT,
                             `school_name` VARCHAR(200) NOT NULL COMMENT 'Σχολείο',
                             `directorate_id` INTEGER NOT NULL COMMENT 'Διεύθυνση Εκπαίδευσης Σχολείου',
                              FOREIGN KEY (`directorate_id`) REFERENCES " . $dbTrnsprtTables['table_directorate'] . "(`directorate_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                              PRIMARY KEY (`school_id`)
                            ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbTrnsprtTables['table_school'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        

        /* CREATE TABLE admapp_trnsprt_transport */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbTrnsprtTables['table_transport'] .
                          " (`transport_id` INTEGER NOT NULL AUTO_INCREMENT,
                             `transport_submissiondate` DATE NOT NULL COMMENT 'Ημερομηνία Αίτησης Έγκρισης',
                             `transport_startdate` DATE NOT NULL COMMENT 'Έναρξη Μετακίνησης',
                             `transport_enddate` DATE NOT NULL COMMENT 'Λήξη Μετακίνησης',
                             `transport_teachers` VARCHAR(1000) NOT NULL COMMENT 'Μετακινούμενοι Εκπαιδευτικοί',
                             `transport_students` VARCHAR(2000) COMMENT 'Μετακινούμενοι Μαθητές',
                             `meeting_id` INTEGER NOT NULL,
                             `school_id` INTEGER NOT NULL,
                              FOREIGN KEY (`meeting_id`) REFERENCES " . $dbTrnsprtTables['table_meeting'] . "(`meeting_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                              FOREIGN KEY (`school_id`) REFERENCES " . $dbTrnsprtTables['table_school'] . "(`school_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                              PRIMARY KEY (`transport_id`)
                            ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbTrnsprtTables['table_transport'] . ". *** \n");
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
        ];
        
        $dbTrnsprtTables = array_reverse($dbTrnsprtTables);
        $i = 0;
        foreach ($dbTrnsprtTables as $dbtable) {
            Console::stdout(++$i . ". Dropping table " . $dbtable . ".\n");
            $this->dropTable($dbtable);
        }
    }
    
}
