<?php

use yii\db\Migration;

class m180115_091711_position_school_type extends Migration
{
    public function safeUp()
    {
        Yii::$app->db->createCommand("SET SQL_MODE='ALLOW_INVALID_DATES'")->execute();
        $this->addColumn(
            '{{%stposition}}',
            'school_type',
        $this->smallInteger()
            ->notNull()
            ->defaultValue(1)
            ->after('[[title]]')
            ->comment('2 for KEDDY')
        );
    }

    public function safeDown()
    {
        Yii::$app->db->createCommand("SET SQL_MODE='ALLOW_INVALID_DATES'")->execute();
        $this->dropColumn('{{%stposition}}', 'school_type');
    }
}
