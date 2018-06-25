<?php

use yii\db\Migration;
use app\traits\DbMigrates;

class m180622_053620_add_leave_extra_reasons extends Migration
{
    use DbMigrates;

    public function safeUp()
    {
        $tableOptions = $this->utf8CollateOptions();
        $this->allowInvalidDates();

        for ($fieldnum = 4; $fieldnum <= 10; $fieldnum++) {
            $this->addColumn('{{%leave}}', "extra_reason{$fieldnum}", $this->string(250)->null()->defaultValue(null));
        }
    }

    public function safeDown()
    {
        $tableOptions = $this->utf8CollateOptions();
        $this->allowInvalidDates();

        for ($fieldnum = 4; $fieldnum <= 10; $fieldnum++) {
            $this->dropColumn('{{%leave}}', "extra_reason{$fieldnum}");
        }
    }
}
