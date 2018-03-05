<?php

use yii\db\Migration;

class m171122_101454_prefectures extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        Yii::$app->db->createCommand("SET SQL_MODE='ALLOW_INVALID_DATES'")->execute();

        $this->createTable('{{%stprefecture}}', [
            'id' => $this->primaryKey(),
            'region' => $this->string(150)->notNull()->defaultValue(''),
            'prefecture' => $this->string(150)->notNull()->unique(),
            'symbol' => $this->char(1)->notNull()->unique()->comment('textual code for batch imports'),
            ], $tableOptions);

        // $this->batchInsert('{{%stprefecture}}', ['region', 'prefecture', 'symbol'], [
        //     ['ΚΡΗΤΗΣ', 'ΗΡΑΚΛΕΙΟΥ', 'Η'],
        //     ['ΚΡΗΤΗΣ', 'ΛΑΣΙΘΙΟΥ', 'Λ'],
        //     ['ΚΡΗΤΗΣ', 'ΡΕΘΥΜΝΟΥ', 'Ρ'],
        //     ['ΚΡΗΤΗΣ', 'ΧΑΝΙΩΝ', 'Χ'],
        // ]);

        $this->addColumn('{{%stposition}}', 'prefecture_id', $this->integer()->after('specialisation_id'));
        $this->addForeignKey('fk_prefecture_id', '{{%stposition}}', 'prefecture_id', '{{%stprefecture}}', 'id', 'RESTRICT', 'CASCADE');
    }

    public function safeDown()
    {
        Yii::$app->db->createCommand("SET SQL_MODE='ALLOW_INVALID_DATES'")->execute();

        $this->dropForeignKey('fk_prefecture_id', '{{%stposition}}');
        $this->dropColumn('{{%stposition}}', 'prefecture_id');

        $this->dropTable('{{%stprefecture}}');
    }
}
