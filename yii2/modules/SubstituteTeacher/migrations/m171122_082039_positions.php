<?php

use yii\db\Migration;

class m171122_082039_positions extends Migration
{

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        Yii::$app->db->createCommand("SET SQL_MODE='ALLOW_INVALID_DATES'")->execute();

        $this->createTable('{{%stposition}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(500)->notNull()
                ->comment('Ususally the school name'),
            'operation_id' => $this->integer()->defaultValue(null),
            'specialisation_id' => $this->integer()->defaultValue(null),
            'teachers_count' => $this->smallInteger()->unsigned()->notNull()
                ->comment('Original Teachers number'),
            'hours_count' => $this->smallInteger()->unsigned()->notNull()
                ->comment('Original Total teaching hours'),
            'whole_teacher_hours' => $this->smallInteger()->unsigned()->notNull()
                ->comment('Keep track of how many hours define a whole slot for a teacher'),
            'covered_teachers_count' => $this->smallInteger()->unsigned()->notNull(),
            'covered_hours_count' => $this->smallInteger()->unsigned()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultValue(0),
            'updated_at' => $this->timestamp()->notNull()->defaultValue(new yii\db\Expression('NOW()')),
            ], $tableOptions);
        $this->createIndex('idx_stposition_by_operation', '{{%stposition}}', 'operation_id');
        $this->createIndex('idx_stposition_by_specialisation', '{{%stposition}}', 'specialisation_id');
        $this->addForeignKey('fk_stposition_operation', '{{%stposition}}', 'operation_id', '{{%stoperation}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_stposition_specialisation', '{{%stposition}}', 'specialisation_id', '{{%specialisation}}', 'id', 'RESTRICT', 'CASCADE');
        $this->createIndex('idx_stposition_by_title', '{{%stposition}}', 'title(20)');

        $this->createTable('{{%stcall}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(500)->notNull(),
            'description' => $this->text()->notNull()->defaultValue(''),
            'application_start' => $this->timestamp()->notNull()->defaultValue(0),
            'application_end' => $this->timestamp()->notNull()->defaultValue(0),
            'created_at' => $this->timestamp()->notNull()->defaultValue(0),
            'updated_at' => $this->timestamp()->notNull()->defaultValue(new yii\db\Expression('NOW()')),
            ], $tableOptions);
        $this->createIndex('idx_stcall_by_title', '{{%stcall}}', 'title(20)');
        $this->createIndex('idx_stcall_by_application_start', '{{%stcall}}', 'application_start');

        // create junction table to associate operations to specialisations
        $this->createTable('{{%stcall_position}}', [
            'id' => $this->primaryKey(),
            'group' => $this->integer()->unsigned()->defaultValue(0),
            'call_id' => $this->integer()->defaultValue(null),
            'position_id' => $this->integer()->defaultValue(null),
            'teachers_count' => $this->smallInteger()->unsigned()->notNull()
                ->comment('Offered teachers number'),
            'hours_count' => $this->smallInteger()->unsigned()->notNull()
                ->comment('Offered teaching hours'),
            'created_at' => $this->timestamp()->notNull()->defaultValue(0),
            'updated_at' => $this->timestamp()->notNull()->defaultValue(new yii\db\Expression('NOW()')),
            ], $tableOptions);
        $this->createIndex('call_idx', '{{%stcall_position}}', 'call_id');
        $this->createIndex('operation_idx', '{{%stcall_position}}', 'position_id');
        $this->createIndex('group_idx', '{{%stcall_position}}', ['call_id', 'group']);
        $this->addForeignKey('fk_stcall_position_call', '{{%stcall_position}}', 'call_id', '{{%stcall}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_stcall_position_position', '{{%stcall_position}}', 'position_id', '{{%stposition}}', 'id', 'RESTRICT', 'CASCADE');
    }

    public function safeDown()
    {
        Yii::$app->db->createCommand("SET SQL_MODE='ALLOW_INVALID_DATES'")->execute();

        $this->dropForeignKey('fk_stcall_position_position', '{{%stcall_position}}');
        $this->dropForeignKey('fk_stcall_position_call', '{{%stcall_position}}');
        $this->dropTable('{{%stcall_position}}');

        $this->dropTable('{{%stcall}}');

        $this->dropForeignKey('fk_stposition_operation', '{{%stposition}}');
        $this->dropForeignKey('fk_stposition_specialisation', '{{%stposition}}');
        $this->dropTable('{{%stposition}}');
    }
}
