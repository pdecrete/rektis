<?php

use yii\db\Migration;
use yii\db\Expression;
use app\traits\DbMigrates;

class m180625_075853_placement extends Migration
{
    use DbMigrates;

    public function safeUp()
    {
        $tableOptions = $this->utf8CollateOptions();
        $this->allowInvalidDates();

        $this->createTable('{{%stplacement}}', [
            'id' => $this->primaryKey(),
            'teacher_board_id' => $this->integer()->notNull(),
            'call_id' => $this->integer()->null()->defaultvalue(null)->comment('if not null points to standard procedure application'),
            'date' => $this->date()->notNull(),
            'decision_board' => $this->string(200)->notNull()->defaultValue(''),
            'decision' => $this->string(200)->notNull()->defaultValue(''),
            'comments' => $this->text()->notNull()->defaultValue(''),
            'altered' => $this->boolean()->notNull()->defaultValue(false),
            'altered_at' => $this->timestamp()->null()->defaultValue(null),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'deleted_at' => $this->timestamp()->null()->defaultValue(null),
            'created_at' => $this->timestamp()->notNull()->defaultValue(0),
            'updated_at' => $this->timestamp()->notNull()->defaultValue(new Expression('NOW()')),
        ], $tableOptions);
        $this->createIndex('idx_stplacement_by_deleted', '{{%stplacement}}', 'deleted');
        $this->createIndex('idx_stplacement_by_altered', '{{%stplacement}}', 'altered');
        $this->createIndex('idx_stplacement_by_teacher', '{{%stplacement}}', 'teacher_board_id');
        $this->createIndex('idx_stplacement_by_decision', '{{%stplacement}}', 'decision');
        $this->createIndex('idx_stplacement_by_date', '{{%stplacement}}', 'date');
        $this->addForeignKey('fk_stplacement_teacher_board', '{{%stplacement}}', 'teacher_board_id', '{{%stteacher_board}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_stplacement_call', '{{%stplacement}}', 'call_id', '{{%stcall}}', 'id', 'RESTRICT', 'CASCADE');

        $this->createTable('{{%stplacement_position}}', [
            'id' => $this->primaryKey(),
            'placement_id' => $this->integer()->defaultValue(null),
            'position_id' => $this->integer()->defaultValue(null),
            'teachers_count' => $this->smallInteger()->unsigned()->notNull()->comment('Offered teachers number'),
            'hours_count' => $this->smallInteger()->unsigned()->notNull()->comment('Offered teaching hours'),
            'created_at' => $this->timestamp()->notNull()->defaultValue(0),
            'updated_at' => $this->timestamp()->notNull()->defaultValue(new Expression('NOW()')),
        ], $tableOptions);
        $this->createIndex('placement_idx', '{{%stplacement_position}}', 'placement_id');
        $this->createIndex('position_idx', '{{%stplacement_position}}', 'position_id');
        $this->addForeignKey('fk_stplacement_position_placement', '{{%stplacement_position}}', 'placement_id', '{{%stplacement}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_stplacement_position_position', '{{%stplacement_position}}', 'position_id', '{{%stposition}}', 'id', 'RESTRICT', 'CASCADE');
    }

    public function safeDown()
    {
        $this->allowInvalidDates();

        $this->dropForeignKey('fk_stplacement_position_placement', '{{%stplacement_position}}');
        $this->dropForeignKey('fk_stplacement_position_position', '{{%stplacement_position}}');
        $this->dropTable('{{%stplacement_position}}');

        $this->dropForeignKey('fk_stplacement_teacher_board', '{{%stplacement}}');
        $this->dropForeignKey('fk_stplacement_call', '{{%stplacement}}');
        $this->dropTable('{{%stplacement}}');
    }
}
