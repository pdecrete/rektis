<?php

use yii\db\Migration;
use app\traits\DbMigrates;

class m180725_110948_call_teachers extends Migration
{
    use DbMigrates;

    public function safeUp()
    {
        $this->allowInvalidDates();

        $this->addColumn('{{%stcall_teacher_specialisation}}', 'teachers_call', $this->smallInteger()->unsigned()->notNull()->defaultValue(0)->comment('The number of teachers to call for applications'));
        $this->alterColumn('{{%stcall_teacher_specialisation}}', 'teachers', $this->smallInteger()->unsigned()->notNull()->defaultValue(0)->comment('The number of teachers to appoint'));
    }

    public function safeDown()
    {
        $this->allowInvalidDates();

        $this->dropColumn('{{%stcall_teacher_specialisation}}', 'teachers_call');
    }
}
