<?php

use yii\db\Migration;

class m180116_065653_teachers extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        Yii::$app->db->createCommand("SET SQL_MODE='ALLOW_INVALID_DATES'")->execute();

        // the main pool of teacher data
        $this->createTable('{{%stteacher_registry}}', [
            'id' => $this->primaryKey(),
            'specialisation_id' => $this->integer()->defaultValue(null),
            'gender' => $this->char(1)->notNull()->defaultValue(''),
            'surname' => $this->string(100)->notNull()->defaultValue(''),
            'firstname' => $this->string(100)->notNull()->defaultValue(''),
            'fathername' => $this->string(100)->notNull()->defaultValue(''),
            'mothername' => $this->string(100)->notNull()->defaultValue(''),
            'marital_status' => $this->char(1)->notNull()->defaultValue(''),
            'protected_children' => $this->smallInteger()->unsigned()->notNull()->defaultValue(0),
            'mobile_phone' => $this->string(20)->notNull()->defaultValue(''),
            'home_phone' => $this->string(20)->notNull()->defaultValue(''),
            'work_phone' => $this->string(20)->notNull()->defaultValue(''),
            'home_address' => $this->string()->notNull()->defaultValue(''),
            'city' => $this->string(100)->notNull()->defaultValue(''),
            'postal_code' => $this->string(10)->notNull()->defaultValue(''),
            'social_security_number' => $this->string(11)->notNull()->defaultValue('')->comment('ΑΜΚΑ'),
            'tax_identification_number' => $this->string(9)->notNull()->defaultValue('')->comment('ΑΦΜ'),
            'tax_service' => $this->string(100)->notNull()->defaultValue('')->comment('ΔΟΥ'),
            'identity_number' => $this->string(30)->notNull()->defaultValue('')->comment('ΑΔΤ'),
            'bank' => $this->string(100)->notNull()->defaultValue(''),
            'iban' => $this->string(34)->notNull()->defaultValue(''),
            //
            'email' => $this->string(150)->notNull()->defaultValue(''),
            'birthdate' => $this->date()->defaultValue(null),
            'birthplace' => $this->string(100)->notNull()->defaultValue(''),
            'comments' => $this->text()->notNull()->defaultValue(''),
            'created_at' => $this->timestamp()->notNull()->defaultValue(0),
            'updated_at' => $this->timestamp()->notNull()->defaultValue(new yii\db\Expression('NOW()')),
            ], $tableOptions);
        $this->createIndex('idx_stteacher_registry_specialisation', '{{%stteacher_registry}}', ['specialisation_id']);
        $this->addForeignKey('fk_stteacher_registry_specialisation_specialisation', '{{%stteacher_registry}}', 'specialisation_id', '{{%specialisation}}', 'id', 'RESTRICT', 'CASCADE');
        $this->createIndex('idx_stteacher_registry_identity_number_unique', '{{%stteacher_registry}}', 'identity_number', true);
        $this->createIndex('idx_stteacher_registry_social_security_number_unique', '{{%stteacher_registry}}', 'social_security_number', true);
        $this->createIndex('idx_stteacher_registry_tax_identification_number_unique', '{{%stteacher_registry}}', 'tax_identification_number', true);
        $this->addCommentOnTable('{{%stteacher_registry}}', 'Main repository of teachers information');

        // per year eligible teachers
        $this->createTable('{{%stteacher}}', [
            'id' => $this->primaryKey(),
            'registry_id' => $this->integer(),
            'year' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->unsigned()->notNull()->defaultValue(0)->comment('Service status, i.e. 1 for appointed'),
            'points' => $this->decimal(9, 3)->unsigned()->notNull()->defaultValue(0.0),
            // TODO: this should be enriched with json data or multiple properties
        ]);
        $this->addForeignKey('fk_teacher_registry_id', '{{%stteacher}}', 'registry_id', '{{%stteacher_registry}}', 'id', 'SET NULL', 'CASCADE');
        $this->createIndex('idx_stteacher_by_year', '{{%stteacher}}', ['year']);
        $this->createIndex('idx_stteacher_unique_by_year', '{{%stteacher}}', ['year', 'registry_id'], true);
        $this->addCommentOnTable('{{%stteacher}}', 'Per year repository of eligible teachers holding appointment information');

        // teachers audit of calls, applications, denials, etc.
        $this->createTable('{{%stteacher_status_audit}}', [
            'id' => $this->primaryKey(),
            'teacher_id' => $this->integer(),
            'status' => $this->smallInteger()->unsigned()->notNull()->defaultValue(0),
            'status_ts' => $this->timestamp()->notNull()->defaultValue(new yii\db\Expression('NOW()')),
        ]);
        $this->addForeignKey('fk_teacher_status_audit_teacher_id', '{{%stteacher_status_audit}}', 'teacher_id', '{{%stteacher}}', 'id', 'SET NULL', 'CASCADE');
        $this->createIndex('idx_teacher_status_audit_by_teacher', '{{%stteacher_status_audit}}', ['teacher_id', 'status_ts']);
        $this->addCommentOnTable('{{%stteacher_status_audit}}', 'Holds the teacher history of actions (call for hiring, application date, etc)');

        // teachers preference order for appointment
        $this->createTable('{{%stplacement_preference}}', [
            'id' => $this->primaryKey(),
            'teacher_id' => $this->integer(),
            'prefecture_id' => $this->integer(),
            'school_type' => $this->integer(),
            'order' => $this->smallInteger()->notNull()
        ]);
        $this->addForeignKey('fk_placement_preference_teacher_id', '{{%stplacement_preference}}', 'teacher_id', '{{%stteacher}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_placement_preference_prefecture_id', '{{%stplacement_preference}}', 'prefecture_id', '{{%stprefecture}}', 'id', 'SET NULL', 'CASCADE');
        $this->createIndex('idx_placement_preference_unique', '{{%stplacement_preference}}', ['teacher_id', 'prefecture_id', 'school_type', 'order'], true);
        $this->addCommentOnTable('{{%stplacement_preference}}', 'Holds prefecture and school type preferences');
    }

    public function safeDown()
    {
        Yii::$app->db->createCommand("SET SQL_MODE='ALLOW_INVALID_DATES'")->execute();

        $this->dropForeignKey('fk_teacher_status_audit_teacher_id', '{{%stteacher_status_audit}}');
        $this->dropTable('{{%stteacher_status_audit}}');

        $this->dropForeignKey('fk_placement_preference_teacher_id', '{{%stplacement_preference}}');
        $this->dropForeignKey('fk_placement_preference_prefecture_id', '{{%stplacement_preference}}');
        $this->dropTable('{{%stplacement_preference}}');

        $this->dropForeignKey('fk_teacher_registry_id', '{{%stteacher}}');
        $this->dropTable('{{%stteacher}}');

        $this->dropForeignKey('fk_stteacher_registry_specialisation_specialisation', '{{%stteacher_registry}}');
        $this->dropTable('{{%stteacher_registry}}');
    }
}
