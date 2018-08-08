<?php

use yii\db\Migration;
use app\traits\DbMigrates;

class m180808_112648_phones extends Migration
{
    use DbMigrates;

    public function safeUp()
    {
        $this->allowInvalidDates();

        $this->alterColumn('{{%stteacher_registry}}', 'mobile_phone', $this->string(40)->notNull()->defaultValue(''));
        $this->alterColumn('{{%stteacher_registry}}', 'home_phone', $this->string(40)->notNull()->defaultValue(''));
        $this->alterColumn('{{%stteacher_registry}}', 'work_phone', $this->string(40)->notNull()->defaultValue(''));
    }

    public function safeDown()
    {
        $this->allowInvalidDates();

        $this->alterColumn('{{%stteacher_registry}}', 'mobile_phone', $this->string(20)->notNull()->defaultValue(''));
        $this->alterColumn('{{%stteacher_registry}}', 'home_phone', $this->string(20)->notNull()->defaultValue(''));
        $this->alterColumn('{{%stteacher_registry}}', 'work_phone', $this->string(20)->notNull()->defaultValue(''));
    }

}
