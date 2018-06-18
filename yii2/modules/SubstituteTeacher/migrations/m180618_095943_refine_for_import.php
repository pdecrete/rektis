<?php

use yii\db\Migration;

class m180618_095943_refine_for_import extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        Yii::$app->db->createCommand("SET SQL_MODE='ALLOW_INVALID_DATES'")->execute();

        $this->dropIndex('idx_stteacher_registry_social_security_number_unique', '{{%stteacher_registry}}');
        $this->alterColumn('{{%stteacher_registry}}', 'social_security_number', $this->string(11)->null()->defaultValue(null)->comment('ΑΜΚΑ'));
        $this->createIndex('idx_stteacher_registry_social_security_number', '{{%stteacher_registry}}', 'social_security_number', false);
    }

    public function safeDown()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        Yii::$app->db->createCommand("SET SQL_MODE='ALLOW_INVALID_DATES'")->execute();

        $this->dropIndex('idx_stteacher_registry_social_security_number', '{{%stteacher_registry}}');
        $this->alterColumn('{{%stteacher_registry}}', 'social_security_number', $this->string(11)->notNull()->defaultValue('')->comment('ΑΜΚΑ'));
        $this->createIndex('idx_stteacher_registry_social_security_number_unique', '{{%stteacher_registry}}', 'social_security_number', true);
    }

}
