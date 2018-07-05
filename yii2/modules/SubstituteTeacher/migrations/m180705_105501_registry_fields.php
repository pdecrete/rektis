<?php

use yii\db\Migration;
use app\traits\DbMigrates;
use yii\db\Expression;

class m180705_105501_registry_fields extends Migration
{
    use DbMigrates;

    public function safeUp()
    {
        $this->allowInvalidDates();

        $this->addColumn('{{%stteacher_registry}}', 'ama', $this->string(20)->notNull()->defaultValue('')->comment('Αριθμός Μητρώου Ασφαλισμένου'));
        $this->addColumn('{{%stteacher_registry}}', 'efka_facility', $this->string(100)->null()->defaultValue(null)->comment('Κατάστημα ΕΦΚΑ'));
        $this->addColumn('{{%stteacher_registry}}', 'municipality', $this->string(100)->null()->defaultValue(null)->comment('Δήμος πολιτογράφησης'));        
    }
    
    public function safeDown()
    {
        $this->allowInvalidDates();

        $this->dropColumn('{{%stteacher_registry}}', 'ama');
        $this->dropColumn('{{%stteacher_registry}}', 'efka_facility');
        $this->dropColumn('{{%stteacher_registry}}', 'municipality');
    }

}
