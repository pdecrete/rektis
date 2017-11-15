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
            'table_expenditure' => $this->db->tablePrefix . 'finance_expenditure',
            'table_invoice' => $this->db->tablePrefix . 'finance_invoice',
            'table_supplier' => $this->db->tablePrefix . 'finance_supplier',
            'table_expenddeduction' => $this->db->tablePrefix . 'finance_expenddeduction',
            'table_expenditurestate' => $this->db->tablePrefix . 'finance_expenditurestate',
            'table_deduction' => $this->db->tablePrefix . 'finance_deduction',
            'table_state' => $this->db->tablePrefix . 'finance_state'
        ];
        
        //$transaction = $this->db->beginTransaction();
        //try{
        /* CREATE TABLE addmap_finance_year */
        $create_command = "CREATE TABLE IF NOT EXISTS " . $dbFinTables['table_year'] .
                              " (`year` INTEGER NOT NULL,
                                `year_credit` INTEGER NOT NULL,
                                `year_lock` BOOLEAN NOT NULL,
                                 PRIMARY KEY (`year`)
                               ) " . $tableOptions;
        Console::stdout("Creating table " . $dbFinTables['table_year'] . ".\n");
        Console::stdout("SQL Command: " . $create_command);
        Yii::$app->db->createCommand($create_command)->execute();
            
        /* CREATE TABLE addmap_finance_year */
        $create_command  = "CREATE TABLE IF NOT EXISTS " . $dbFinTables['table_kae'] .
                               " (`kae_id` INTEGER NOT NULL AUTO_INCREMENT,
                                  `year_credit` INTEGER NOT NULL,
                                  `year_lock` BOOLEAN NOT NULL,
                                   PRIMARY KEY (`kae_id`)
                                 )  " . $tableOptions;
        Console::stdout("Creating table " . $dbFinTables['table_kae'] . ".\n");
        Console::stdout("SQL Command: " . $create_command);
        Yii::$app->db->createCommand($create_command)->execute();
            
        /* CREATE TABLE addmap_finance_year */
  /*          $create_command = "CREATE TABLE IF NOT EXISTS " . $dbFinTables['table_kaecredit'] .
                              "(`kaecredit_id` INTEGER NOT NULL AUTO_INCREMENT,
                                `kaecredit_amount` INTEGER NOT NULL,
                                `kaecredit_date` BOOLEAN NOT NULL,
                                'year' INTEGER,
                                'kae_id INTEGER',
                                 INDEX year_indx (year),
                                 INDEX kae_id_indx (kae_id),
                                 FOREIGN KEY (year) REFERENCES " . $dbFinTables['table_year'](year) . ",
                                 FOREIGN KEY (kae_id) REFERENCES " . $dbFinTables['table_kae'](kae_id) . ",
                                 PRIMARY KEY (`kaecredit_id`)
                               ) " . $tableOptions;
            Console::stdout("Creating table " . $dbFinTables['table_kaecredit'] . ".\n");
            Console::stdout("SQL Command: " . $create_command);
            Yii::$app->db->createCommand($create_command)->execute();*/
        /*}
        catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }*/
    }

    public function safeDown()
    {
        $dbFinTables = [
            'table_year' => $this->db->tablePrefix . 'finance_year',
            'table_kae' => $this->db->tablePrefix . 'finance_kae',
            //'table_kaecredit' => $this->db->tablePrefix . 'finance_kaecredit',
           /* 'table_kaewithdrawal' => $this->db->tablePrefix . 'finance_kaewithdrawal',
            'table_expenditure' => $this->db->tablePrefix . 'finance_expenditure',
            'table_invoice' => $this->db->tablePrefix . 'finance_invoice',
            'table_supplier' => $this->db->tablePrefix . 'finance_supplier',
            'table_expenddeduction' => $this->db->tablePrefix . 'finance_expenddeduction',
            'table_expenditurestate' => $this->db->tablePrefix . 'finance_expenditurestate',
            'table_deduction' => $this->db->tablePrefix . 'finance_deduction',
            'table_state' => $this->db->tablePrefix . 'finance_state'*/
        ];
    
        foreach ($dbFinTables as $dbtable) {
            Console::stdout("Dropping table " . $dbtable . ".\n");
            $this->dropTable($dbtable);
        }
    }
}
