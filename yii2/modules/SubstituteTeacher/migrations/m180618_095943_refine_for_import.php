<?php

use yii\db\Migration;

class m180618_095943_refine_for_import extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        Yii::$app->db->createCommand("SET SQL_MODE='ALLOW_INVALID_DATES'")->execute();

        // $this->alterColumn('{{%stteacher_registry}}', 'gender', $this->char(1)->null()->defaultValue(null));
        // $this->alterColumn('{{%stteacher_registry}}', 'mothername', $this->string(100)->null()->defaultValue(null));
        // $this->alterColumn('{{%stteacher_registry}}', 'marital_status', $this->char(1)->null()->defaultValue(null));
        // $this->alterColumn('{{%stteacher_registry}}', 'mobile_phone', $this->string(20)->null()->defaultValue(null));
        // $this->alterColumn('{{%stteacher_registry}}', 'home_address', $this->string()->null()->defaultValue(null));
        // $this->alterColumn('{{%stteacher_registry}}', 'city', $this->string(100)->null()->defaultValue(null));
        // $this->alterColumn('{{%stteacher_registry}}', 'postal_code', $this->string(10)->null()->defaultValue(null));
        $this->alterColumn('{{%stteacher_registry}}', 'social_security_number', $this->string(11)->null()->defaultValue(null)->comment('ΑΜΚΑ'));
        // $this->alterColumn('{{%stteacher_registry}}', 'tax_service', $this->string(100)->null()->defaultValue(null)->comment('ΔΟΥ'));
        // $this->alterColumn('{{%stteacher_registry}}', 'bank', $this->string(100)->null()->defaultValue(null));
        // $this->alterColumn('{{%stteacher_registry}}', 'iban', $this->string(34)->null()->defaultValue(null));
        // $this->alterColumn('{{%stteacher_registry}}', 'email', $this->string(150)->null()->defaultValue(null));
        // $this->alterColumn('{{%stteacher_registry}}', 'birthdate', $this->date()->defaultValue(null));
        // $this->alterColumn('{{%stteacher_registry}}', 'birthplace', $this->string(100)->null()->defaultValue(null));
    }

    public function safeDown()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        Yii::$app->db->createCommand("SET SQL_MODE='ALLOW_INVALID_DATES'")->execute();

        $this->alterColumn('{{%stteacher_registry}}', 'social_security_number', $this->string(11)->notNull()->defaultValue('')->comment('ΑΜΚΑ'));
    }

}
