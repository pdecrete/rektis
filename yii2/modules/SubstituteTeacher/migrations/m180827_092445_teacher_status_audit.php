<?php

use yii\db\Migration;
use app\traits\DbMigrates;

class m180827_092445_teacher_status_audit extends Migration
{
    use DbMigrates;

    public function safeUp()
    {
        $this->allowInvalidDates();

        $this->addColumn('{{%stteacher_status_audit}}', 'audit', $this->string(80)->notNull()->defaultValue(''));
        $this->addColumn('{{%stteacher_status_audit}}', 'data', $this->text()->null()->defaultValue(null)->comment('Json field with context information'));

        $this->renameColumn('{{%stteacher_status_audit}}', 'status_ts', 'audit_ts');
    }

    public function safeDown()
    {
        $this->allowInvalidDates();

        $this->dropColumn('{{%stteacher_status_audit}}', 'audit');
        $this->dropColumn('{{%stteacher_status_audit}}', 'data');

        $this->renameColumn('{{%stteacher_status_audit}}', 'audit_ts', 'status_ts');
    }
}
