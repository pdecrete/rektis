<?php

use yii\db\Migration;
use app\traits\DbMigrates;

class m180628_071158_operation_support_material extends Migration
{
    use DbMigrates;

    public function safeUp()
    {
        $tableOptions = $this->utf8CollateOptions();
        $this->allowInvalidDates();

        $this->addColumn('{{%stoperation}}', 'contract_template', $this->string(500)->null()->defaultValue(null)->comment('Έγγραφο σύμβασης'));
        $this->addColumn('{{%stoperation}}', 'summary_template', $this->string(500)->null()->defaultValue(null)->comment('Έγγραφο περίληψης σύμβασης'));
        $this->addColumn('{{%stoperation}}', 'export_template', $this->string(500)->null()->defaultValue(null)->comment('Πρότυπο λογιστικό φύλλο για exports'));
    }

    public function safeDown()
    {
        $this->allowInvalidDates();

        $this->dropColumn('{{%stoperation}}', 'export_template');
        $this->dropColumn('{{%stoperation}}', 'summary_template');
        $this->dropColumn('{{%stoperation}}', 'contract_template');
    }
}
