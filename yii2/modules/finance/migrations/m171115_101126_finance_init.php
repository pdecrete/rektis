<?php
use yii\db\Migration;
use yii\helpers\Console;

class m171115_101126_finance_init extends Migration
{
    //admapp_finance_year
    //admapp_finance_kae
    //admapp_finance_kaecredit
    //admapp_finance
    
    public function safeUp()
    {
        /*
         * Money datatype
         * Used BIGINT for easier operations on money values + for adaptable currency
         */
        $moneyDatatype = "BIGINT";
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $dbFinTables = [
            'table_year' => $this->db->tablePrefix . 'finance_year',
            'table_kae' => $this->db->tablePrefix . 'finance_kae',
            'table_kaecredit' => $this->db->tablePrefix . 'finance_kaecredit',
            'table_kaecreditpercentage' => $this->db->tablePrefix . 'finance_kaecreditpercentage',
            'table_kaewithdrawal' => $this->db->tablePrefix . 'finance_kaewithdrawal',
            'table_taxoffice' => $this->db->tablePrefix . "finance_taxoffice",
            'table_supplier' => $this->db->tablePrefix . 'finance_supplier',
            'table_expenditure' => $this->db->tablePrefix . 'finance_expenditure',
            'table_expendwithdrawal' => $this->db->tablePrefix . 'finance_expendwithdrawal',
            'table_invoicetype' => $this->db->tablePrefix . 'finance_invoicetype',
            'table_invoice' => $this->db->tablePrefix . 'finance_invoice',
            'table_deduction' => $this->db->tablePrefix . 'finance_deduction',
            'table_expenddeduction' => $this->db->tablePrefix . 'finance_expenddeduction',
            'table_state' => $this->db->tablePrefix . 'finance_state',
            'table_expenditurestate' => $this->db->tablePrefix . 'finance_expenditurestate',
            'table_fpa' => $this->db->tablePrefix . 'finance_fpa'
        ];
        $i = 1;
        /* CREATE TABLE admapp_finance_year */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbFinTables['table_year'] .
                          " (`year` INTEGER NOT NULL,
                             `year_credit` " . $moneyDatatype . " UNSIGNED NOT NULL,
                             `year_iscurrent` BOOLEAN NOT NULL DEFAULT 0,
                             `year_lock` BOOLEAN NOT NULL DEFAULT 0,
                              PRIMARY KEY (`year`)
                            ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbFinTables['table_year'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
            
        /* CREATE TABLE admapp_finance_kae */
        $create_command  = "CREATE TABLE IF NOT EXISTS " . $dbFinTables['table_kae'] .
                           " (`kae_id` INTEGER NOT NULL,                              
                              `kae_title` VARCHAR(255) NOT NULL,
                              `kae_description` VARCHAR(1024),
                               PRIMARY KEY (`kae_id`)
                             )  " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbFinTables['table_kae'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        $insert_command = "INSERT INTO " . $dbFinTables['table_kae'] . "(kae_id, kae_title) VALUES ";
        Yii::$app->db->createCommand($insert_command . "(0516, 'Υπερωριακή αποζημίωση εκπαιδευτικών (μονίμων και αναπλητωτών)')")->execute();
        Yii::$app->db->createCommand($insert_command . "(0719, 'Λοιπά έξοδα μετακίνησης')")->execute();
        Yii::$app->db->createCommand($insert_command . "(0721, 'Ημερήσια αποζημίωση')")->execute();
        Yii::$app->db->createCommand($insert_command . "(0722, 'Έξοδα διανυκτέρευσης')")->execute();
        Yii::$app->db->createCommand($insert_command . "(0813, 'Μισθώματα κτιρίων')")->execute();
        Yii::$app->db->createCommand($insert_command . "(0824, 'Υποχρεώσεις από παροχή τηλεπικοινωνιακών υπηρεσιών (τέλη, μισθώματα και δαπάνες εγκατάστασης)')")->execute();
        Yii::$app->db->createCommand($insert_command . "(0831, 'Ύδρευση και άδρευση')")->execute();
        Yii::$app->db->createCommand($insert_command . "(0832, 'Φωτισμός και κίνηση (με ηλεκτρισμό ή φωταέριο)')")->execute();
        Yii::$app->db->createCommand($insert_command . "(0841, 'Διαφημίσεις και Δημοσιεύσεις γενικά')")->execute();
        Yii::$app->db->createCommand($insert_command . "(0843, 'Εκδόσεις - εκτυπώσεις - βιβλιοδεσία (περιλαμβάνεται και η βοήθεια χάρτου)')")->execute();
//10
        Yii::$app->db->createCommand($insert_command . "(0845, 'Κάθε είδους δαπάνες δημοσίων σχέσεων')")->execute();
        Yii::$app->db->createCommand($insert_command . "(0851, 'Αμοιβές για συντήρηση και επισκευή κτιρίων γενικά, εγκαταστάσεων στρατωνισμού, ελλιμενισμού')")->execute();
        Yii::$app->db->createCommand($insert_command . "(0875, 'Αμοιβές για δαπάνες καθαριότητας')")->execute();
        Yii::$app->db->createCommand($insert_command . "(1111, 'Προμήθεια χαρτιού, γραφικών ειδών και λοιπών συναφών υλικών')")->execute();
        Yii::$app->db->createCommand($insert_command . "(1231, 'Προμήθεια ειδών καθαριότητας')")->execute();
        Yii::$app->db->createCommand($insert_command . "(1311, 'Προμήθεια ειδών συντήρησης και επισκευής ηλεκτρικών εγκαταστάσεων')")->execute();
        Yii::$app->db->createCommand($insert_command . "(1329, 'Προμήθεια ειδών συντήρησης και επισκευής κάθε είδους εξοπλισμού')")->execute();
        Yii::$app->db->createCommand($insert_command . "(1512, 'Προμήθεια καυσίμων θέρμανσης και δαπάνες κοινοχρήστων')")->execute();
        Yii::$app->db->createCommand($insert_command . "(1699, 'Διάφορες λοιπές δαπάνες')")->execute();
        Yii::$app->db->createCommand($insert_command . "(1711, 'Προμήθεια επίπλων')")->execute();
//20
        Yii::$app->db->createCommand($insert_command . "(1712, 'Προμήθεια συσκευών θέρμανσης και κλιματισμού')")->execute();
        Yii::$app->db->createCommand($insert_command . "(1713, 'Προμήθεια γραφομηχανών,μηχανημάτων φωτοαντιγραφής κλπ. μηχανών γραφείου')")->execute();
        Yii::$app->db->createCommand($insert_command . "(1723, 'Προμήθεια ηλεκτρονικών υπολογιστών, προγραμμάτων και λοιπών υλικών')")->execute();
        Yii::$app->db->createCommand($insert_command . "(2224, 'Επιχορήγηση στις σχολικές επιτροπές και σχολικές εφορίες')")->execute();
        Yii::$app->db->createCommand($insert_command . "(9511, 'Πρόσθετες παροχές')")->execute();
        Yii::$app->db->createCommand($insert_command . "(9711, 'Υπόλοιπα μετακινήσεων')")->execute();
        Yii::$app->db->createCommand($insert_command . "(9821, 'Υποχρεώσεις από παροχή τηλ/κων υπηρεσιών')")->execute();
        Yii::$app->db->createCommand($insert_command . "(9831, 'Δαπάνες Ύδρευσης και Άδρευσης')")->execute();
        Yii::$app->db->createCommand($insert_command . "(9832, 'Δαπάνες Ηλεκτρικής Ενέργειας')")->execute();
//29
        /* CREATE TABLE admapp_finance_kaecredit */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbFinTables['table_kaecredit'] .
                          "(`kaecredit_id` INTEGER NOT NULL AUTO_INCREMENT,
                            `kaecredit_amount` " . $moneyDatatype . " UNSIGNED NOT NULL,
                            `kaecredit_date` DATETIME NOT NULL,
                            `kaecredit_updated` DATETIME,
                            `year` INTEGER NOT NULL,
                            `kae_id` INTEGER NOT NULL,
                             FOREIGN KEY (`year`) REFERENCES " . $dbFinTables['table_year'] . "(`year`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                             FOREIGN KEY (`kae_id`) REFERENCES " . $dbFinTables['table_kae'] . "(`kae_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                             PRIMARY KEY (`kaecredit_id`),
                             UNIQUE KEY (`year`, `kae_id`)
                           ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbFinTables['table_kaecredit'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();

        /* CREATE TABLE admapp_finance_kaecreditpercentage */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbFinTables['table_kaecreditpercentage'] .
                          "(`kaeperc_id` INTEGER NOT NULL AUTO_INCREMENT,
                            `kaeperc_percentage` SMALLINT UNSIGNED NOT NULL CHECK (kaeperc_percentage >= 0 AND kaeperc_percentage <= 10000),
                            `kaeperc_date` DATETIME NOT NULL,
                            `kaeperc_decision` VARCHAR(255),
                            `kaecredit_id` INTEGER NOT NULL,
                             FOREIGN KEY (`kaecredit_id`) REFERENCES " . $dbFinTables['table_kaecredit'] . "(`kaecredit_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                             PRIMARY KEY (`kaeperc_id`)
                           ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbFinTables['table_kaecreditpercentage'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
                
        /* CREATE TABLE admapp_finance_kaewithdrawal */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbFinTables['table_kaewithdrawal'] .
                          "(`kaewithdr_id` INTEGER NOT NULL AUTO_INCREMENT,
                            `kaewithdr_amount` " . $moneyDatatype . " UNSIGNED NOT NULL,
                            `kaewithdr_decision` VARCHAR(255) NOT NULL,
                            `kaewithdr_date` DATETIME NOT NULL,
                            `kaecredit_id` INTEGER NOT NULL,
                             FOREIGN KEY (`kaecredit_id`) REFERENCES " . $dbFinTables['table_kaecredit'] . "(`kaecredit_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                             PRIMARY KEY (`kaewithdr_id`)
                           ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbFinTables['table_kaewithdrawal'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        
        /* CREATE TABLE admapp_finance_taxoffice */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbFinTables['table_taxoffice'] . "
                            (`taxoffice_id` INTEGER NOT NULL,
                             `taxoffice_name` VARCHAR(100) NOT NULL,                             
                              PRIMARY KEY (`taxoffice_id`)
                            ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbFinTables['table_taxoffice'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();        
        $insert_command = "INSERT INTO " . $dbFinTables['table_taxoffice'] . "(taxoffice_id, taxoffice_name) VALUES ";
        Yii::$app->db->createCommand($insert_command . "(8110, 'Ηρακλείου')")->execute();
        Yii::$app->db->createCommand($insert_command . "(8431, 'Χανίων')")->execute();
        Yii::$app->db->createCommand($insert_command . "(8221, 'Αγίου Νικολάου')")->execute();
        Yii::$app->db->createCommand($insert_command . "(8341, 'Ρεθύμνου')")->execute();
        
        /* CREATE TABLE admapp_finance_supplier */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbFinTables['table_supplier'] .
                          "(`suppl_id` INTEGER NOT NULL AUTO_INCREMENT,
                            `suppl_name` VARCHAR(255) NOT NULL,
                            `suppl_vat` INTEGER NOT NULL,
                            `suppl_address` VARCHAR(255),
                            `suppl_phone` INTEGER,
                            `suppl_fax` INTEGER,
                            `suppl_iban` VARCHAR(27) NOT NULL,
                            `suppl_employerid` VARCHAR(100) NOT NULL,
                            `taxoffice_id` INTEGER NOT NULL,
                             FOREIGN KEY (`taxoffice_id`) REFERENCES " . $dbFinTables['table_taxoffice'] . "(`taxoffice_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                             PRIMARY KEY (`suppl_id`),
                             UNIQUE KEY (`suppl_vat`)
                           ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbFinTables['table_supplier'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();

        /* CREATE TABLE admapp_finance_expenditure */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbFinTables['table_expenditure'] .
                          "(`exp_id` INTEGER NOT NULL AUTO_INCREMENT,
                            `exp_amount` " . $moneyDatatype . " UNSIGNED NOT NULL,
                            `exp_date` INTEGER NOT NULL,
                            `exp_lock` VARCHAR(255) NOT NULL,
                            `exp_deleted` BOOLEAN NOT NULL DEFAULT 0,
                            `suppl_id` INTEGER NOT NULL,
                            `fpa_value` SMALLINT UNSIGNED NOT NULL CHECK (fpa_value >= 0 AND fpa_value <= 10000), 
                             PRIMARY KEY (`exp_id`),
                             FOREIGN KEY (`suppl_id`) REFERENCES " . $dbFinTables['table_supplier'] . "(`suppl_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . "
                           ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbFinTables['table_expenditure'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();

        /* CREATE TABLE admapp_finance_expendwithdrawal */
        $create_command  =  "CREATE TABLE IF NOT EXISTS " . $dbFinTables['table_expendwithdrawal'] .
                            " ( `kaewithdr_id` INTEGER,
                                `exp_id` INTEGER,
                                PRIMARY KEY (`kaewithdr_id`, `exp_id`),
                                FOREIGN KEY (`exp_id`) REFERENCES " . $dbFinTables['table_expenditure'] . "(`exp_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                                FOREIGN KEY (`kaewithdr_id`) REFERENCES " . $dbFinTables['table_kaewithdrawal'] . "(`kaewithdr_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . "
                              )  " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbFinTables['table_expendwithdrawal'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
                
        
        /* CREATE TABLE admapp_finance_invoicetype */
        $create_command  = "CREATE TABLE IF NOT EXISTS " . $dbFinTables['table_invoicetype'] .
                           " (`invtype_id` INTEGER NOT NULL AUTO_INCREMENT,
                              `invtype_title` VARCHAR(255) NOT NULL,
                               PRIMARY KEY (`invtype_id`)
                             )  " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbFinTables['table_invoicetype'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        $insert_command = "INSERT INTO " . $dbFinTables['table_invoicetype'] . "(invtype_title) VALUES ";
        Yii::$app->db->createCommand($insert_command . "('Τιμολόγιο Παροχής Υπηρεσιών')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Απόδειξη Παροχής Υπηρεσιών')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Τιμολόγιο Δελτίου Αποστολής')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Απόδειξη Δελτίου Αποστολής')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Τιμολόγιο Πώλησης - Δελτίο Αποστολής')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Κατάσταση Πληρωμής')")->execute();
        Yii::$app->db->createCommand($insert_command . "('Λογαριασμός ΔΕΗ/Τηλεπικοινωνιών κτλ.')")->execute();
        
        /* CREATE TABLE admapp_finance_invoice */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbFinTables['table_invoice'] .
                          "(`inv_id` INTEGER NOT NULL AUTO_INCREMENT,
                            `inv_number` VARCHAR(255) NOT NULL, 
                            `inv_date` INTEGER NOT NULL,
                            `inv_order` VARCHAR(255) NOT NULL,
                            `inv_deleted` BOOLEAN NOT NULL DEFAULT 0,
                            `suppl_id` INTEGER NOT NULL,
                            `exp_id` INTEGER NOT NULL,
                            `invtype_id` INTEGER NOT NULL,
                             PRIMARY KEY (`inv_id`),
                             FOREIGN KEY (`suppl_id`) REFERENCES " . $dbFinTables['table_supplier'] . "(`suppl_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                             FOREIGN KEY (`exp_id`) REFERENCES " . $dbFinTables['table_expenditure'] . "(`exp_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                             FOREIGN KEY (`invtype_id`) REFERENCES " . $dbFinTables['table_invoicetype'] . "(`invtype_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                             UNIQUE KEY (`exp_id`)
                           ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbFinTables['table_invoice'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();

        /* CREATE TABLE admapp_finance_deduction */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbFinTables['table_deduction'] .
                          "(`deduct_id` INTEGER NOT NULL AUTO_INCREMENT,
                            `deduct_name` VARCHAR(100) NOT NULL,
                            `deduct_description` VARCHAR(1000),
                            `deduct_date` DATETIME NOT NULL,
                            `deduct_percentage` SMALLINT UNSIGNED NOT NULL CHECK (deduct_percentage >= 0 AND deduct_percentage <= 10000),
                            `deduct_downlimit` " . $moneyDatatype . " UNSIGNED NOT NULL DEFAULT 0,
                            `deduct_uplimit` " . $moneyDatatype . " UNSIGNED DEFAULT NULL,
                            `deduct_obsolete` BOOLEAN NOT NULL DEFAULT 0,
                             PRIMARY KEY (`deduct_id`)
                           ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbFinTables['table_deduction'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        $insert_command = "INSERT INTO " . $dbFinTables['table_deduction'] . 
                          "(deduct_id, deduct_name, deduct_date, deduct_percentage, deduct_downlimit) VALUES ";
        Yii::$app->db->createCommand($insert_command . "(1, 'Παροχή υπηρεσιών άνω των 150 ευρώ', NOW(), 400, 15000)")->execute();
        Yii::$app->db->createCommand($insert_command . "(2, 'Αγορά υλικών αγαθών άνω των 150 ευρώ', NOW(), 800, 15000)")->execute();
        Yii::$app->db->createCommand($insert_command . "(3, 'Δαπάνη καθαριότητας', NOW(), 10, 0)")->execute();
        
        /* CREATE TABLE admapp_finance_expenddeduction */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbFinTables['table_expenddeduction'] .
                          "(`exp_id` INTEGER,
                            `deduct_id` INTEGER,
                             PRIMARY KEY (`exp_id`, `deduct_id`),
                             FOREIGN KEY (`exp_id`) REFERENCES " . $dbFinTables['table_expenditure'] . "(`exp_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                             FOREIGN KEY (`deduct_id`) REFERENCES " . $dbFinTables['table_deduction'] . "(`deduct_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . "
                           ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbFinTables['table_expenddeduction'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        
        /* CREATE TABLE admapp_finance_state */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbFinTables['table_state'] .
                          "(`state_id` INTEGER NOT NULL AUTO_INCREMENT,
                            `state_name` VARCHAR(100),
                             PRIMARY KEY (`state_id`)
                           ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbFinTables['table_state'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        $insert_command = "INSERT INTO " . $dbFinTables['table_state'] . "(state_name) VALUES ";
        Yii::$app->db->createCommand($insert_command . "('ΑΡΧΙΚΟΠΟΙΗΘΗΚΕ')")->execute();
        Yii::$app->db->createCommand($insert_command . "('ΑΠΑΙΤΗΘΗΚΕ')")->execute();
        Yii::$app->db->createCommand($insert_command . "('ΕΝΤΑΛΜΑΤΟΠΟΙΗΘΗΚΕ')")->execute();
        Yii::$app->db->createCommand($insert_command . "('ΟΛΟΚΛΗΡΩΘΗΚΕ')")->execute();
        
        /* CREATE TABLE admapp_finance_expenditurestate */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbFinTables['table_expenditurestate'] .
                          "(`exp_id` INTEGER NOT NULL,
                            `state_id` INTEGER NOT NULL,
                            `expstate_date` DATETIME NOT NULL,
                            `expstate_comment` VARCHAR(200),
                             PRIMARY KEY (`exp_id`, `state_id`),
                             FOREIGN KEY (`exp_id`) REFERENCES " . $dbFinTables['table_expenditure'] . "(`exp_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                             FOREIGN KEY (`state_id`) REFERENCES " . $dbFinTables['table_state'] . "(`state_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . "
                           ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbFinTables['table_expenditurestate'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        
        
        /* CREATE TABLE admapp_finance_expenditurestate */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbFinTables['table_fpa'] .
                          "(`fpa_value` SMALLINT UNSIGNED NOT NULL CHECK (fpa_value >= 0 AND fpa_value <= 10000),
                            PRIMARY KEY (`fpa_value`)
                           ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbFinTables['table_fpa'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        $insert_command = "INSERT INTO " . $dbFinTables['table_fpa'] . "(fpa_value) VALUES ";
        Yii::$app->db->createCommand($insert_command . "(1300)")->execute();
        Yii::$app->db->createCommand($insert_command . "(2400)")->execute();
        
    }

    public function safeDown()
    {
        $dbFinTables = [
            'table_year' => $this->db->tablePrefix . 'finance_year',
            'table_kae' => $this->db->tablePrefix . 'finance_kae',
            'table_kaecredit' => $this->db->tablePrefix . 'finance_kaecredit',
            'table_kaecreditpercentage' => $this->db->tablePrefix . 'finance_kaecreditpercentage',
            'table_kaewithdrawal' => $this->db->tablePrefix . 'finance_kaewithdrawal',
            'table_taxoffice' => $this->db->tablePrefix . "finance_taxoffice",
            'table_supplier' => $this->db->tablePrefix . 'finance_supplier',
            'table_expenditure' => $this->db->tablePrefix . 'finance_expenditure',
            'table_expendwithdrawal' => $this->db->tablePrefix . 'finance_expendwithdrawal',
            'table_invoicetype' => $this->db->tablePrefix . 'finance_invoicetype',
            'table_invoice' => $this->db->tablePrefix . 'finance_invoice',
            'table_deduction' => $this->db->tablePrefix . 'finance_deduction',
            'table_expenddeduction' => $this->db->tablePrefix . 'finance_expenddeduction',
            'table_state' => $this->db->tablePrefix . 'finance_state',
            'table_expenditurestate' => $this->db->tablePrefix . 'finance_expenditurestate',
            'table_fpa' => $this->db->tablePrefix . 'finance_fpa'
        ];
        $dbFinTables = array_reverse($dbFinTables);
        $i = 0;
        foreach ($dbFinTables as $dbtable) {
            Console::stdout(++$i . ". Dropping table " . $dbtable . ".\n");
            $this->dropTable($dbtable);
        }
    }
}
