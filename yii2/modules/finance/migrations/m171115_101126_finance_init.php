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
         * https://opendata.stackexchange.com/questions/10346/what-specifications-are-out-there-for-the-precision-required-to-store-money
         * https://rietta.com/blog/2012/03/03/best-data-types-for-currencymoney-in/
         * https://stackoverflow.com/questions/13030368/best-data-type-to-store-money-values-in-mysql
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
            'table_kaewithdrawal' => $this->db->tablePrefix . 'finance_kaewithdrawal',
            'table_taxoffice' => $this->db->tablePrefix . "finance_taxoffice",
            'table_supplier' => $this->db->tablePrefix . 'finance_supplier',
            'table_expenditure' => $this->db->tablePrefix . 'finance_expenditure',
            'table_invoice' => $this->db->tablePrefix . 'finance_invoice',
            'table_deduction' => $this->db->tablePrefix . 'finance_deduction',
            'table_expenddeduction' => $this->db->tablePrefix . 'finance_expenddeduction',
            'table_state' => $this->db->tablePrefix . 'finance_state',
            'table_expenditurestate' => $this->db->tablePrefix . 'finance_expenditurestate'
        ];
        $i = 1;
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbFinTables['table_year'] .
                          " (`year` INTEGER NOT NULL,
                             `year_credit` " . $moneyDatatype . " UNSIGNED NOT NULL,
                             `year_lock` BOOLEAN NOT NULL DEFAULT 0,
                              PRIMARY KEY (`year`)
                            ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbFinTables['table_year'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
            
        /* CREATE TABLE addmap_finance_kae */
        $create_command  = "CREATE TABLE IF NOT EXISTS " . $dbFinTables['table_kae'] .
                           " (`kae_id` INTEGER NOT NULL AUTO_INCREMENT,
                              `kae_title` VARCHAR(255) NOT NULL,
                              `kae_description` VARCHAR(1024),
                               PRIMARY KEY (`kae_id`)
                             )  " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbFinTables['table_kae'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
            
        /* CREATE TABLE addmap_finance_kaecredit */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbFinTables['table_kaecredit'] .
                          "(`kaecredit_id` INTEGER NOT NULL AUTO_INCREMENT,
                            `kaecredit_amount` " . $moneyDatatype . " UNSIGNED NOT NULL,
                            `kaecredit_date` DATETIME NOT NULL,
                            `year` INTEGER,
                            `kae_id` INTEGER,
                             FOREIGN KEY (`year`) REFERENCES " . $dbFinTables['table_year'] . "(`year`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                             FOREIGN KEY (`kae_id`) REFERENCES " . $dbFinTables['table_kae'] . "(`kae_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                             PRIMARY KEY (`kaecredit_id`)
                           ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbFinTables['table_kaecredit'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        
        /* CREATE TABLE addmap_finance_kaewithdrawal */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbFinTables['table_kaewithdrawal'] .
                          "(`kaewithdr_id` INTEGER NOT NULL AUTO_INCREMENT,
                            `kaewithdr_amount` " . $moneyDatatype . " UNSIGNED NOT NULL,
                            `kaewithdr_decision` VARCHAR(255) NOT NULL,
                            `kaewithdr_date` DATETIME NOT NULL,
                            `kaecredit_id` INTEGER,
                             FOREIGN KEY (`kaecredit_id`) REFERENCES " . $dbFinTables['table_kaecredit'] . "(`kaecredit_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                             PRIMARY KEY (`kaewithdr_id`)
                           ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbFinTables['table_kaewithdrawal'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        
        /* CREATE TABLE addmap_finance_taxoffice */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbFinTables['table_taxoffice'] . "
                            (`taxoffice_id` INTEGER NOT NULL,
                             `taxoffice_name` VARCHAR(100) NOT NULL,
                             `taxoffice_prefecture` VARCHAR(100),
                              PRIMARY KEY (`taxoffice_id`)
                            ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbFinTables['table_taxoffice'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();        
        
        /* CREATE TABLE addmap_finance_supplier */
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
                             PRIMARY KEY (`suppl_id`)
                           ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbFinTables['table_supplier'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();

        /* CREATE TABLE addmap_finance_expenditure */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbFinTables['table_expenditure'] .
                          "(`exp_id` INTEGER NOT NULL AUTO_INCREMENT,
                            `exp_amount` " . $moneyDatatype . " UNSIGNED NOT NULL,
                            `exp_date` INTEGER NOT NULL,
                            `exp_lock` VARCHAR(255) NOT NULL,
                            `kaewithdr_id` INTEGER,
                            `suppl_id` INTEGER,
                             PRIMARY KEY (`exp_id`),
                             FOREIGN KEY (`suppl_id`) REFERENCES " . $dbFinTables['table_supplier'] . "(`suppl_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                             FOREIGN KEY (`kaewithdr_id`) REFERENCES " . $dbFinTables['table_kaewithdrawal'] . "(`kaewithdr_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . "
                           ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbFinTables['table_expenditure'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        
        /* CREATE TABLE addmap_finance_invoice */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbFinTables['table_invoice'] .
                          "(`inv_id` INTEGER NOT NULL AUTO_INCREMENT,
                            `inv_number` VARCHAR(255) NOT NULL, 
                            `inv_date` INTEGER NOT NULL,
                            `inv_order` VARCHAR(255) NOT NULL,
                            `inv_amount`  " . $moneyDatatype . ",
                            `inv_dedections` TEXT,
                            `inv_roundings` SMALLINT,
                            `suppl_id` INTEGER,
                            `exp_id` INTEGER,
                             PRIMARY KEY (`inv_id`),
                             FOREIGN KEY (`suppl_id`) REFERENCES " . $dbFinTables['table_supplier'] . "(`suppl_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                             FOREIGN KEY (`exp_id`) REFERENCES " . $dbFinTables['table_expenditure'] . "(`exp_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . "
                           ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbFinTables['table_invoice'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();

        /* CREATE TABLE addmap_finance_deduction */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbFinTables['table_deduction'] .
                          "(`deduct_id` INTEGER NOT NULL AUTO_INCREMENT,
                            `deduct_name` VARCHAR(100) NOT NULL,
                            `deduct_percentage` DECIMAL(3, 2) NOT NULL CHECK (deduct_percentage >= 0.00 AND deduct_percentage <= 100.00),
                            `deduct_description` VARCHAR(1000),
                            `deduct_date` DATETIME NOT NULL,
                            `detuct_obsolete` BOOLEAN NOT NULL DEFAULT 0,
                             PRIMARY KEY (`deduct_id`)
                           ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbFinTables['table_deduction'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        
        /* CREATE TABLE addmap_finance_expenddeduction */
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
        
        /* CREATE TABLE addmap_finance_state */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbFinTables['table_state'] .
                          "(`state_id` INTEGER NOT NULL AUTO_INCREMENT,
                            `state_name` VARCHAR(100),
                             PRIMARY KEY (`state_id`)
                           ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbFinTables['table_state'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
        
        /* CREATE TABLE addmap_finance_expenditurestate */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbFinTables['table_expenditurestate'] .
                          "(`exp_id` INTEGER,
                            `state_id` INTEGER,
                            `expstate_date` DATETIME NOT NULL,
                            `expstate_comment` VARCHAR(200),
                             PRIMARY KEY (`exp_id`, `state_id`),
                             FOREIGN KEY (`exp_id`) REFERENCES " . $dbFinTables['table_expenditure'] . "(`exp_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . ",
                             FOREIGN KEY (`state_id`) REFERENCES " . $dbFinTables['table_state'] . "(`state_id`) ON DELETE RESTRICT ON UPDATE RESTRICT " . "
                           ) " . $tableOptions;
        Console::stdout("\n" . $i++ . ". *** Creating table " . $dbFinTables['table_expenditurestate'] . ". *** \n");
        Console::stdout("SQL Command: " . $create_command . "\n");
        Yii::$app->db->createCommand($create_command)->execute();
    }

    public function safeDown()
    {
        $dbFinTables = [
            'table_year' => $this->db->tablePrefix . 'finance_year',
            'table_kae' => $this->db->tablePrefix . 'finance_kae',
            'table_kaecredit' => $this->db->tablePrefix . 'finance_kaecredit',
            'table_kaewithdrawal' => $this->db->tablePrefix . 'finance_kaewithdrawal',
            'table_taxoffice' => $this->db->tablePrefix . "finance_taxoffice",
            'table_supplier' => $this->db->tablePrefix . 'finance_supplier',
            'table_expenditure' => $this->db->tablePrefix . 'finance_expenditure',
            'table_invoice' => $this->db->tablePrefix . 'finance_invoice',
            'table_deduction' => $this->db->tablePrefix . 'finance_deduction',
            'table_expenddeduction' => $this->db->tablePrefix . 'finance_expenddeduction',
            'table_state' => $this->db->tablePrefix . 'finance_state',
            'table_expenditurestate' => $this->db->tablePrefix . 'finance_expenditurestate'
        ];
        $dbFinTables = array_reverse($dbFinTables);
        $i = 0;
        foreach ($dbFinTables as $dbtable) {
            Console::stdout(++$i . ". Dropping table " . $dbtable . ".\n");
            $this->dropTable($dbtable);
        }
    }
}