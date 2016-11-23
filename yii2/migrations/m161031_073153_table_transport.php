<?php

use yii\helpers\Console;
use yii\db\Migration;

class m161031_073153_table_transport extends Migration
{
    protected $tables = [
        ['transport_distance', "
				`id` integer NOT NULL AUTO_INCREMENT,
				`name` varchar(255) NOT NULL,
				`distance` float NOT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `distance_name` (`name`)
			"],   
        ['transport_type', "
				`id` integer NOT NULL AUTO_INCREMENT,
				`name` varchar(100) NOT NULL,
				`description` longtext NOT NULL,
				`create_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`update_ts` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				`deleted` tinyint(1) NOT NULL DEFAULT '0',
				`templatefilename1` varchar(255) DEFAULT NULL,
				`templatefilename2` varchar(255) DEFAULT NULL,
				`templatefilename3` varchar(255) DEFAULT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `transport_name` (`name`),
				KEY `admapp_transport_type_by_deleted` (`deleted`) 
			"],
        ['transport_mode', "
				`id` integer NOT NULL AUTO_INCREMENT,
				`name` varchar(100) NOT NULL,
				`value` float NULL DEFAULT NULL COMMENT 'ΑΝΑ ΧΛΜ',
				`out_limit` integer NULL DEFAULT NULL COMMENT 'ΟΡΙΟ ΓΙΑ ΔΙΑΝΥΚΤΕΡΕΥΣΗ',
				`deleted` tinyint(1) NOT NULL DEFAULT '0',
				PRIMARY KEY (`id`),
				UNIQUE KEY `transport_mode_name` (`name`) 
			"],
        ['transport_status', "
				`id` integer NOT NULL AUTO_INCREMENT,
				`name` varchar(100) NOT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `transport_status_name` (`name`) 
			"],
        ['transport_funds', "
				`id` integer NOT NULL AUTO_INCREMENT,
				`name` varchar(100) NULL DEFAULT NULL COMMENT 'Αρ.Πρωτ',
				`date` date NULL DEFAULT NULL COMMENT 'Ημερομηνία',
				`ada` varchar(20) NULL DEFAULT NULL COMMENT 'ΑΔΑ',
				`service` integer NULL DEFAULT NULL COMMENT 'Υπηρεσία',
				`code` varchar(20) NULL DEFAULT NULL COMMENT 'Τακτ.Προϋπολογισμού',
				`kae` varchar(10) NULL DEFAULT NULL COMMENT 'KAE',
				`amount` float NULL DEFAULT NULL COMMENT 'Ποσό',
				`count_flag` boolean DEFAULT 1 COMMENT 'Προσμέτρηση στην υπηρεσία',
				PRIMARY KEY (`id`),
				KEY `service_fk_index` (`service`)    
			"],
        ['transport', "
				`id` integer NOT NULL AUTO_INCREMENT,
				`employee` integer NULL DEFAULT NULL COMMENT 'Υπάλληλος',
				`type` integer NULL DEFAULT NULL COMMENT 'Τύπος μετακίνησης',
				`decision_protocol` integer NULL DEFAULT NULL COMMENT 'Πρωτόκολλο απόφασης',
				`decision_protocol_date` date NULL DEFAULT NULL COMMENT 'Ημερομηνία απόφασης',
				`application_protocol` integer NULL DEFAULT NULL COMMENT 'Πρωτόκολλο αίτησης',
				`application_protocol_date` date NULL DEFAULT NULL COMMENT 'Ημερομηνία πρωτοκόλλου αίτησης',
				`application_date` date NULL DEFAULT NULL COMMENT 'Ημερομηνία  αίτησης',
				`accompanying_document` varchar(100) NULL DEFAULT NULL COMMENT 'Συνοδευτικά έγγραφα (βεβαίωση, δήλωση για αναρρωτική, κλπ.',
				`start_date` date NOT NULL COMMENT 'Ημερομηνία αναχώρησης',
				`end_date` date NOT NULL COMMENT 'Ημερομηνία επιστροφής',
				`reason` varchar(200) NULL DEFAULT NULL COMMENT 'Σκοπός',
				`from_to` integer NULL DEFAULT NULL COMMENT 'Από-Προς',
				`base` varchar(100) NULL DEFAULT NULL COMMENT 'Έδρα μετακίνησης',
				`days_applied` smallint(5) unsigned NOT NULL COMMENT 'Ημέρες εκτός έδρας',
				`klm` float NULL DEFAULT NULL COMMENT 'Απόσταση σε χιλιόμετρα', 
				`mode` integer NULL DEFAULT NULL COMMENT 'Μέσο μετακίνησης',
				`night_reimb` float DEFAULT '0.00' COMMENT 'Αποζημίωση διανυκτέρευσης',
				`ticket_value` float DEFAULT '0.00' COMMENT 'Αντίτιμο εισιτηρίου',
				`klm_reimb` float DEFAULT '0.00' COMMENT 'Χιλιομετρική αποζημίωση',
				`days_out` float DEFAULT '0',
				`day_reimb` float DEFAULT '0.00' COMMENT 'Ημερήσια αποζημίωση',
				`reimbursement` float DEFAULT '0.00' COMMENT 'Συνολικό κόστος μετακίνησης',
				`mtpy` float DEFAULT '0.00' COMMENT 'ΜΤΠΥ',
				`pay_amount` float DEFAULT '0.00' COMMENT 'Πληρωτέο Υπόλοιπο',
				`code719` float DEFAULT '0.00' COMMENT 'ΚΑΕ 719',
				`code721` float DEFAULT '0.00' COMMENT 'ΚΑΕ 721',
				`code722` float DEFAULT '0.00' COMMENT 'ΚΑΕ 722',
				`count_flag` boolean DEFAULT 1 COMMENT 'Επιβάρυνση υπηρεσίας',
				`fund1` integer NULL DEFAULT NULL,
				`fund2` integer NULL DEFAULT NULL,
				`fund3` integer NULL DEFAULT NULL,
				`expense_details` varchar(255) NULL DEFAULT NULL COMMENT 'Σχόλια δαπάνης (ΚΑΕ)',
				`comment` longtext NULL DEFAULT NULL,
				`create_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`update_ts` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				`deleted` tinyint(1) NOT NULL DEFAULT '0',
				PRIMARY KEY (`id`),
				KEY `employee_fk_index` (`employee`),
				KEY `transport_type_fk_index` (`type`),
				KEY `transport_type_journal_fk_index` (`type`),
				KEY `transport_mode_fk_index` (`mode`),
				KEY `transport_funds1_fk_index` (`fund1`),
				KEY `transport_funds2_fk_index` (`fund2`),
				KEY `transport_funds3_fk_index` (`fund3`),
				KEY `admapp_transport_by_deleted` (`deleted`)
            "],        
        ['transport_status_date', "
				`id` integer NOT NULL AUTO_INCREMENT,
				`transport` integer NULL DEFAULT NULL,
				`status` integer NULL DEFAULT NULL COMMENT 'Κατάσταση',
				`status_date` date NOT NULL COMMENT 'Ημερομηνία κατάστασης',
				PRIMARY KEY (`id`)
			"],
        ['transport_print', "
				`id` integer NOT NULL AUTO_INCREMENT,
				`transport` integer DEFAULT NULL COMMENT 'Μετακίνηση',
				`filename` varchar(255) NOT NULL,
				`doctype` smallint NULL DEFAULT NULL,
				`create_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`send_ts` timestamp NULL DEFAULT NULL,
				`to_emails` varchar(1000) DEFAULT NULL,
				PRIMARY KEY (`id`),
				KEY `transport_fk_index` (`transport`)    
			"],
	];

    public function safeUp()
    {
        $tables_cnt = count($this->tables);
        for ($i = 0; $i < $tables_cnt; $i++) {
            $table_realname = $this->tables[$i][0];
            $columns = $this->tables[$i][1];

            $table_name = $this->db->tablePrefix . $table_realname;

            Yii::$app->db->createCommand("create table if not exists `{$table_name}` ({$columns}) engine=InnoDB charset=utf8;")
                      ->execute();

            $table_schema = Yii::$app->db->schema->getTableSchema($table_name);
            if ($table_schema === null) {
                Console::stdout("Table '{$table_name}' does not appear to exist; considering create unsuccessful.\n", Console::FG_RED);
            } else {
                Console::stdout("Created table '{$table_name}'.\n", Console::FG_GREEN);
				if ($table_name == 'admapp_transport') {
					$this->addForeignKey('transport_employee_fk', $table_name, 'employee', 'admapp_employee', 'id', 'SET NULL', 'CASCADE');
					$this->addForeignKey('transport_type_fk', $table_name, 'type', 'admapp_transport_type', 'id', 'SET NULL', 'CASCADE');
					$this->addForeignKey('transport_distance_fk', $table_name, 'from_to', 'admapp_transport_distance', 'id', 'SET NULL', 'CASCADE');
					$this->addForeignKey('transport_mode_fk', $table_name, 'mode', 'admapp_transport_mode', 'id', 'SET NULL', 'CASCADE');
					$this->addForeignKey('transport_funds_fk1', $table_name, 'fund1', 'admapp_transport_funds', 'id', 'SET NULL', 'CASCADE');
					$this->addForeignKey('transport_funds_fk2', $table_name, 'fund2', 'admapp_transport_funds', 'id', 'SET NULL', 'CASCADE');
					$this->addForeignKey('transport_funds_fk3', $table_name, 'fund3', 'admapp_transport_funds', 'id', 'SET NULL', 'CASCADE');
				}
				if ($table_name == 'admapp_transport_print') {
					$this->addForeignKey('transport_print_trans_fk', $table_name, 'transport', 'admapp_transport', 'id', 'SET NULL', 'CASCADE');
				}
				if ($table_name == 'admapp_transport_status_date') {
					$this->addForeignKey('transport_status_date_trans_fk', $table_name, 'transport', 'admapp_transport', 'id', 'SET NULL', 'CASCADE');
					$this->addForeignKey('transport_status_date_status_fk', $table_name, 'status', 'admapp_transport_status', 'id', 'SET NULL', 'CASCADE');
				}
				if ($table_name == 'admapp_transport_funds') {
					$this->addForeignKey('transport_funds_service_fk', $table_name, 'service', 'admapp_service', 'id', 'SET NULL', 'CASCADE');
				}
            }
        }
    }

    public function safeDown()
    {
        $tables_cnt = count($this->tables);
        for ($i = $tables_cnt - 1; $i >= 0; $i--) {
            $table_realname = $this->tables[$i][0];
            $columns = $this->tables[$i][1];

            $table_name = $this->db->tablePrefix . $table_realname;
            $table_schema = Yii::$app->db->schema->getTableSchema($table_name);
            if ($table_schema === null) {
                Console::stdout("Table '{$table_name}' does not appear to exist; considering drop successful.\n", Console::FG_YELLOW);
            } else {
                Console::stdout("Dropping table '{$table_name}'.\n");
                $this->dropTable($table_name);
            }
        }
    }

}
