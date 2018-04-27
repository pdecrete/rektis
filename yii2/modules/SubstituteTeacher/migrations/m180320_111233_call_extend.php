<?php

use yii\db\Migration;

class m180320_111233_call_extend extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        Yii::$app->db->createCommand("SET SQL_MODE='ALLOW_INVALID_DATES'")->execute();

        // add teacher board year to call 
        $this->addColumn('{{%stcall}}', 'year', $this->integer()->unsigned()->defaultValue('0')->notNull());
        
        $this->createTable('{{%stcall_teacher_specialisation}}', [
            'id' => $this->primaryKey(),
            'call_id' => $this->integer(),
            'specialisation_id' => $this->integer(),
            'teachers' => $this->smallInteger()->unsigned()->notNull()->defaultValue(0)->comment('The number of teachers to call'),
            'teachers_called' => $this->text()->comment('hold refs to called teachers (after call)'),
        ]);
        $this->addForeignKey('fk_callteacherspecialisation_call_id', '{{%stcall_teacher_specialisation}}', 'call_id', '{{%stcall}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_callteacherspecialisation_specialisation_id', '{{%stcall_teacher_specialisation}}', 'specialisation_id', '{{%specialisation}}', 'id', 'SET NULL', 'CASCADE');
        $this->createIndex('idx_fk_callteacherspecialisation_unique', '{{%stcall_teacher_specialisation}}', ['call_id', 'specialisation_id'], true);
    }

    public function safeDown()
    {
        Yii::$app->db->createCommand("SET SQL_MODE='ALLOW_INVALID_DATES'")->execute();

        $this->dropColumn('{{%stcall}}', 'year');
        
        $this->dropTable('{{%stcall_teacher_specialisation}}');
    }
}
