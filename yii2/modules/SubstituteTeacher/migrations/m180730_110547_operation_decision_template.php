<?php

use yii\db\Migration;
use app\traits\DbMigrates;

class m180730_110547_operation_decision_template extends Migration
{
    use DbMigrates;

    public function safeUp()
    {
        $this->allowInvalidDates();

        $this->addColumn('{{%stoperation}}', 'decision_template', $this->string(500)->null()->defaultValue(null)->comment('Έγγραφο απόφασης τοποθέτησης'));
    }

    public function safeDown()
    {
        $this->allowInvalidDates();

        $this->dropColumn('{{%stoperation}}', 'decision_template');
    }
}
