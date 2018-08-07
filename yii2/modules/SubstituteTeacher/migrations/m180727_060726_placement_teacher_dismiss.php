<?php

use yii\db\Migration;
use app\traits\DbMigrates;

class m180727_060726_placement_teacher_dismiss extends Migration
{
    use DbMigrates;

    public function safeUp()
    {
        $this->allowInvalidDates();

        $this->addColumn('{{%stplacement_teacher}}', 'dismissed', $this->boolean()->notNull()->defaultValue(false)->comment('Απόλυση/λύση σύμβασης'));
        $this->addColumn('{{%stplacement_teacher}}', 'dismissed_at', $this->timestamp()->null()->defaultValue(null));
        $this->dropColumn('{{%stplacement_teacher}}', 'deleted');
        $this->dropColumn('{{%stplacement_teacher}}', 'deleted_at');
    }

    public function safeDown()
    {
        $this->allowInvalidDates();

        $this->addColumn('{{%stplacement_teacher}}', 'deleted', $this->boolean()->notNull()->defaultValue(false));
        $this->addColumn('{{%stplacement_teacher}}', 'deleted_at', $this->timestamp()->null()->defaultValue(null));
        $this->dropColumn('{{%stplacement_teacher}}', 'dismissed');
        $this->dropColumn('{{%stplacement_teacher}}', 'dismissed_at');
    }
}
