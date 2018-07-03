<?php

use yii\db\Migration;
use app\traits\DbMigrates;
use yii\db\Expression;

class m180702_081412_placement_prints extends Migration
{
    use DbMigrates;

    public function safeUp()
    {
        $tableOptions = $this->utf8CollateOptions();
        $this->allowInvalidDates();

        $this->createTable('{{%stplacement_print}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string(50)->notNull()->defaultValue(''), // i.e. 'contract', 'summary'
            'placement_id' => $this->integer()->notNull(), // must be set on all prints
            'placement_teacher_id' => $this->integer()->null()->defaultValue(null), // applicable if print is for specific teacher
            'filename' => $this->string(250)->notNull(), // filename w/out basedir
            'data' => $this->text()->notNull()->defaultValue(''), // data used in print
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'deleted_at' => $this->timestamp()->null()->defaultValue(null),
            'created_at' => $this->timestamp()->notNull()->defaultValue(0),
            'updated_at' => $this->timestamp()->notNull()->defaultValue(new Expression('NOW()')),
        ], $tableOptions);

        $this->createIndex('placement_idx', '{{%stplacement_print}}', 'placement_id');
        $this->createIndex('placement_teacher_idx', '{{%stplacement_print}}', 'placement_teacher_id');
        $this->addForeignKey('fk_stplacement_print_placement', '{{%stplacement_print}}', 'placement_id', '{{%stplacement}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_stplacement_print_placement_teacher', '{{%stplacement_print}}', 'placement_teacher_id', '{{%stplacement_teacher}}', 'id', 'RESTRICT', 'CASCADE');
    }

    public function safeDown()
    {
        $this->allowInvalidDates();

        $this->dropForeignKey('fk_stplacement_print_placement_teacher', '{{%stplacement_print}}');
        $this->dropForeignKey('fk_stplacement_print_placement', '{{%stplacement_print}}');
        $this->dropTable('{{%stplacement_print}}');
    }
}
