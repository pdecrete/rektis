<?php

use yii\db\Migration;

class m171120_122634_operations extends Migration
{

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        Yii::$app->db->createCommand("SET SQL_MODE='ALLOW_INVALID_DATES'")->execute();

        // create core info table 
        $this->createTable('{{%stoperation}}', [
            'id' => $this->primaryKey(),
            'year' => $this->integer()->notNull(),
            'title' => $this->string(500)->notNull(),
            'description' => $this->string(90)->notNull()->defaultValue(''),
            'created_at' => $this->timestamp()->notNull()->defaultValue(0),
            'updated_at' => $this->timestamp()->notNull()->defaultValue(new yii\db\Expression('NOW()')),
            ], $tableOptions);
        $this->createIndex('idx_stoperation_by_year', '{{%stoperation}}', ['year']);

        // create junction table to associate operations to specialisations
        $this->createTable('{{%stoperation_specialisation}}', [
            'id' => $this->primaryKey(),
            'operation_id' => $this->integer()->defaultValue(null),
            'specialisation_id' => $this->integer()->defaultValue(null),
            ], $tableOptions);
        $this->createIndex('operation_idx', '{{%stoperation_specialisation}}', 'operation_id');
        $this->createIndex('specialisation_idx', '{{%stoperation_specialisation}}', 'specialisation_id');
        $this->addForeignKey('fk_stoperation_specialisation_operation', '{{%stoperation_specialisation}}', 'operation_id', '{{%stoperation}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_stoperation_specialisation_specialisation', '{{%stoperation_specialisation}}', 'specialisation_id', '{{%specialisation}}', 'id', 'RESTRICT', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_stoperation_specialisation_specialisation', '{{%stoperation_specialisation}}');
        $this->dropForeignKey('fk_stoperation_specialisation_operation', '{{%stoperation_specialisation}}');
        $this->dropTable('{{%stoperation_specialisation}}');

        $this->dropTable('{{%stoperation}}');
    }
}
