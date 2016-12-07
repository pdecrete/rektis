<?php

use yii\helpers\Console;
use yii\db\Migration;

class m161207_064654_float_to_decimal_fix extends Migration
{
    public function safeUp()
    {
	$table_name = $this->db->tablePrefix . 'transport_funds';
        Console::stdout("Altering table '{$table_name}': Altering field amount - declaring it DECIMAL(10,2).\n");
        Yii::$app->db->createCommand("
		alter table `{$table_name}` CHANGE `amount` `amount` DECIMAL( 10, 2 ) NULL DEFAULT NULL COMMENT 'Ποσό'
		 ") 
		 ->execute();

	$table_name = $this->db->tablePrefix . 'transport_distance';
        Console::stdout("Altering table '{$table_name}': Altering field distance - declaring it DECIMAL(10,1).\n");
        Yii::$app->db->createCommand("
		alter table `{$table_name}` CHANGE `distance` `distance` DECIMAL( 10, 1 ) NOT NULL 
		 ") 
		 ->execute();          

	$table_name = $this->db->tablePrefix . 'transport_mode';
        Console::stdout("Altering table '{$table_name}': Altering field value - declaring it DECIMAL(10,2).\n");
        Yii::$app->db->createCommand("
		alter table `{$table_name}` CHANGE `value` `value` DECIMAL( 10,2 ) NULL DEFAULT NULL COMMENT 'ΑΝΑ ΧΛΜ'
		 ") 
		 ->execute();

	$table_name = $this->db->tablePrefix . 'transport';
        Console::stdout("Altering table '{$table_name}': Altering field klm - declaring it DECIMAL(10,1).\n");
        Yii::$app->db->createCommand("
		alter table `{$table_name}` CHANGE `klm` `klm` DECIMAL( 10, 1 ) NULL DEFAULT NULL COMMENT 'Απόσταση σε χιλιόμετρα'
		 ") 
		 ->execute();
            
        Console::stdout("Altering table '{$table_name}': Altering fields night_reimb, ticket_value, klm_reimb, day_reimb, reimbursement, mtpy, pay_amount, code719, code721, code722 - declaring them DECIMAL(10,2).\n");
        Yii::$app->db->createCommand("
		alter table `{$table_name}` CHANGE `night_reimb` `night_reimb` DECIMAL( 10, 2 ) NULL DEFAULT '0' COMMENT 'Αποζημίωση διανυκτέρευσης' 
		") ->execute();
        Yii::$app->db->createCommand("
		alter table `{$table_name}` CHANGE `ticket_value` `ticket_value` DECIMAL( 10, 2 ) NULL DEFAULT '0' COMMENT 'Αντίτιμο εισιτηρίου' 
		") ->execute();
        Yii::$app->db->createCommand("
		alter table `{$table_name}` CHANGE `klm_reimb` `klm_reimb` DECIMAL( 10, 2 ) NULL DEFAULT '0' COMMENT 'Χιλιομετρική αποζημίωση' 
		") ->execute();
        Yii::$app->db->createCommand("
		alter table `{$table_name}` CHANGE `day_reimb` `day_reimb` DECIMAL( 10, 2 ) NULL DEFAULT '0' COMMENT 'Ημερήσια αποζημίωση' 
		") ->execute();
        Yii::$app->db->createCommand("
		alter table `{$table_name}` CHANGE `reimbursement` `reimbursement` DECIMAL( 10, 2 ) NULL DEFAULT '0' COMMENT 'Συνολικό κόστος μετακίνησης' 
		") ->execute();
        Yii::$app->db->createCommand("
		alter table `{$table_name}` CHANGE `mtpy` `mtpy` DECIMAL( 10, 2 ) NULL DEFAULT '0' COMMENT 'ΜΤΠΥ' 
		") ->execute();
        Yii::$app->db->createCommand("
		alter table `{$table_name}` CHANGE `pay_amount` `pay_amount` DECIMAL( 10, 2 ) NULL DEFAULT '0' COMMENT 'Πληρωτέο Υπόλοιπο' 
		") ->execute();
        Yii::$app->db->createCommand("
		alter table `{$table_name}` CHANGE `code719` `code719` DECIMAL( 10, 2 ) NULL DEFAULT '0' COMMENT 'ΚΑΕ 719' 
		") ->execute();
        Yii::$app->db->createCommand("
		alter table `{$table_name}` CHANGE `code721` `code721` DECIMAL( 10, 2 ) NULL DEFAULT '0' COMMENT 'ΚΑΕ 721' 
		") ->execute();
        Yii::$app->db->createCommand("
		alter table `{$table_name}` CHANGE `code722` `code722` DECIMAL( 10, 2 ) NULL DEFAULT '0' COMMENT 'ΚΑΕ 722' 
		") ->execute();

        Console::stdout("Altering table '{$table_name}': Altering field days_out - declaring it SMALLINT.\n");
        Yii::$app->db->createCommand("
		alter table `{$table_name}` CHANGE `days_out` `days_out` SMALLINT NULL DEFAULT '0'
		 ") 
		 ->execute();
                
            return true;
    }

    public function safeDown()
    {
	$table_name = $this->db->tablePrefix . 'transport_funds';
        Console::stdout("Altering table '{$table_name}': Altering field amount - declaring it Float.\n");
        Yii::$app->db->createCommand("
		alter table `{$table_name}` CHANGE `amount` `amount` Float NULL DEFAULT NULL COMMENT 'Ποσό'
		 ") 
		 ->execute();

	$table_name = $this->db->tablePrefix . 'transport_distance';
        Console::stdout("Altering table '{$table_name}': Altering field distance - declaring it Float.\n");
        Yii::$app->db->createCommand("
		alter table `{$table_name}` CHANGE `distance` `distance` Float NOT NULL 
		 ") 
		 ->execute();          

	$table_name = $this->db->tablePrefix . 'transport_mode';
        Console::stdout("Altering table '{$table_name}': Altering field value - declaring it Float.\n");
        Yii::$app->db->createCommand("
		alter table `{$table_name}` CHANGE `value` `value` Float NULL DEFAULT NULL COMMENT 'ΑΝΑ ΧΛΜ'
		 ") 
		 ->execute();

	$table_name = $this->db->tablePrefix . 'transport';
        Console::stdout("Altering table '{$table_name}': Altering field klm - declaring it Float.\n");
        Yii::$app->db->createCommand("
		alter table `{$table_name}` CHANGE `klm` `klm` Float NULL DEFAULT NULL COMMENT 'Απόσταση σε χιλιόμετρα'
		 ") 
		 ->execute();
            
        Console::stdout("Altering table '{$table_name}': Altering fields night_reimb, ticket_value, klm_reimb, day_reimb, reimbursement, mtpy, pay_amount, code719, code721, code722 - declaring them Float.\n");
        Yii::$app->db->createCommand("
		alter table `{$table_name}` CHANGE `night_reimb` `night_reimb` Float NULL DEFAULT '0' COMMENT 'Αποζημίωση διανυκτέρευσης' 
		") ->execute();
        Yii::$app->db->createCommand("
		alter table `{$table_name}` CHANGE `ticket_value` `ticket_value` Float NULL DEFAULT '0' COMMENT 'Αντίτιμο εισιτηρίου' 
		") ->execute();
        Yii::$app->db->createCommand("
		alter table `{$table_name}` CHANGE `klm_reimb` `klm_reimb` Float NULL DEFAULT '0' COMMENT 'Χιλιομετρική αποζημίωση' 
		") ->execute();
        Yii::$app->db->createCommand("
		alter table `{$table_name}` CHANGE `day_reimb` `day_reimb` Float NULL DEFAULT '0' COMMENT 'Ημερήσια αποζημίωση' 
		") ->execute();
        Yii::$app->db->createCommand("
		alter table `{$table_name}` CHANGE `reimbursement` `reimbursement` Float NULL DEFAULT '0' COMMENT 'Συνολικό κόστος μετακίνησης' 
		") ->execute();
        Yii::$app->db->createCommand("
		alter table `{$table_name}` CHANGE `mtpy` `mtpy` Float NULL DEFAULT '0' COMMENT 'ΜΤΠΥ' 
		") ->execute();
        Yii::$app->db->createCommand("
		alter table `{$table_name}` CHANGE `pay_amount` `pay_amount` Float NULL DEFAULT '0' COMMENT 'Πληρωτέο Υπόλοιπο' 
		") ->execute();
        Yii::$app->db->createCommand("
		alter table `{$table_name}` CHANGE `code719` `code719` Float NULL DEFAULT '0' COMMENT 'ΚΑΕ 719' 
		") ->execute();
        Yii::$app->db->createCommand("
		alter table `{$table_name}` CHANGE `code721` `code721` Float NULL DEFAULT '0' COMMENT 'ΚΑΕ 721' 
		") ->execute();
        Yii::$app->db->createCommand("
		alter table `{$table_name}` CHANGE `code722` `code722` Float NULL DEFAULT '0' COMMENT 'ΚΑΕ 722' 
		") ->execute();

        Console::stdout("Altering table '{$table_name}': Altering field days_out - declaring it Float.\n");
        Yii::$app->db->createCommand("
		alter table `{$table_name}` CHANGE `days_out` `days_out` Float NULL DEFAULT '0'
		 ") 
		 ->execute();
                

            return true;
    }
}
