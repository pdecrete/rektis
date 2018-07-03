<?php

use yii\db\Migration;
use app\traits\DbMigrates;
use yii\helpers\Console;
use yii\db\Expression;

class m180629_060822_placement_mods extends Migration
{
    use DbMigrates;

    public function safeUp()
    {
        if (false === Console::confirm(Console::ansiFormat("This will delete all placement information. Are you sure?", [Console::BG_RED]))) {
            return false;
        }

        $tableOptions = $this->utf8CollateOptions();
        $this->allowInvalidDates();
        
        // empty and modify prior tables
        $this->disableForeignKeyChecks();
        $this->truncateTable('{{%stplacement_position}}');
        $this->truncateTable('{{%stplacement}}');
        $this->enableForeignKeyChecks();

        // new table to separate placement decistions from teachers
        $this->createTable('{{%stplacement_teacher}}', [
            'id' => $this->primaryKey(),
            'placement_id' => $this->integer()->defaultValue(null),
            'teacher_board_id' => $this->integer()->notNull(),
            'comments' => $this->text()->notNull()->defaultValue(''),
            'altered' => $this->boolean()->notNull()->defaultValue(false),
            'altered_at' => $this->timestamp()->null()->defaultValue(null),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'deleted_at' => $this->timestamp()->null()->defaultValue(null),
            'created_at' => $this->timestamp()->notNull()->defaultValue(0),
            'updated_at' => $this->timestamp()->notNull()->defaultValue(new Expression('NOW()')),
        ], $tableOptions);
        $this->createIndex('placement_idx', '{{%stplacement_teacher}}', 'placement_id');
        $this->createIndex('teacher_board_idx', '{{%stplacement_teacher}}', 'teacher_board_id');
        $this->addForeignKey('fk_stplacement_teacher_placement', '{{%stplacement_teacher}}', 'placement_id', '{{%stplacement}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_stplacement_teacher_position', '{{%stplacement_teacher}}', 'teacher_board_id', '{{%stteacher_board}}', 'id', 'RESTRICT', 'CASCADE');

        $this->dropForeignKey('fk_stplacement_teacher_board', '{{%stplacement}}');
        // $this->dropIndex('idx_stplacement_by_teacher', '{{%stplacement}}');
        // $this->dropIndex('idx_stplacement_by_altered', '{{%stplacement}}');
        $this->dropColumn('{{%stplacement}}', 'teacher_board_id');
        $this->dropColumn('{{%stplacement}}', 'altered');
        $this->dropColumn('{{%stplacement}}', 'altered_at');

        $this->dropForeignKey('fk_stplacement_position_placement', '{{%stplacement_position}}');
        $this->dropColumn('{{%stplacement_position}}', 'placement_id');
        $this->addColumn('{{%stplacement_position}}', 'placement_teacher_id', $this->integer()->notNull());
        $this->createIndex('placement_teacher_idx', '{{%stplacement_position}}', 'placement_teacher_id');
        $this->addForeignKey('fk_stplacement_position_placement_teacher', '{{%stplacement_position}}', 'placement_teacher_id', '{{%stplacement_teacher}}', 'id', 'RESTRICT', 'CASCADE');
    }

    public function safeDown()
    {
        if (false === Console::confirm(Console::ansiFormat("This will delete all placement information. Are you sure?", [Console::BG_RED]))) {
            return false;
        }

        $this->allowInvalidDates();
        
        // empty and modify prior tables
        $this->disableForeignKeyChecks();
        $this->truncateTable('{{%stplacement_position}}');
        $this->truncateTable('{{%stplacement_teacher}}');
        $this->truncateTable('{{%stplacement}}');
        $this->enableForeignKeyChecks();

        $this->addColumn('{{%stplacement}}', 'teacher_board_id', $this->integer()->notNull());
        $this->addColumn('{{%stplacement}}', 'altered', $this->boolean()->notNull()->defaultValue(false));
        $this->addColumn('{{%stplacement}}', 'altered_at', $this->timestamp()->null()->defaultValue(null));
        $this->createIndex('idx_stplacement_by_teacher', '{{%stplacement}}', 'teacher_board_id');
        $this->addForeignKey('fk_stplacement_teacher_board', '{{%stplacement}}', 'teacher_board_id', '{{%stteacher_board}}', 'id', 'RESTRICT', 'CASCADE');
        $this->createIndex('idx_stplacement_by_altered', '{{%stplacement}}', 'altered');

        $this->dropForeignKey('fk_stplacement_position_placement_teacher', '{{%stplacement_position}}');
        $this->dropColumn('{{%stplacement_position}}', 'placement_teacher_id');
        $this->addColumn('{{%stplacement_position}}', 'placement_id', $this->integer()->notNull());
        $this->createIndex('placement_idx', '{{%stplacement_position}}', 'placement_id');
        $this->addForeignKey('fk_stplacement_position_placement', '{{%stplacement_position}}', 'placement_id', '{{%stplacement}}', 'id', 'RESTRICT', 'CASCADE');

        $this->dropTable('{{%stplacement_teacher}}');
    }
}
