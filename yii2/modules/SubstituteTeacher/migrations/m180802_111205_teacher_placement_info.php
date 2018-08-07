<?php

use yii\db\Migration;
use app\traits\DbMigrates;

class m180802_111205_teacher_placement_info extends Migration
{
    use DbMigrates;

    public function safeUp()
    {
        $this->allowInvalidDates();

        $this->addColumn('{{%stplacement}}', 'ada', $this->string(200)->null()->defaultValue(null)->comment('ΑΔΑ απόφασης'));
        $this->addColumn('{{%stplacement}}', 'base_contract_start_date', $this->date()->null()->defaultValue(null)->comment('Βάση γα έναρξη σύμβασης'));
        $this->addColumn('{{%stplacement}}', 'base_contract_end_date', $this->date()->null()->defaultValue(null)->comment('Βάση για λήξη σύμβασης'));

        $this->addColumn('{{%stplacement_teacher}}', 'dismissed_ada', $this->string(200)->null()->defaultValue(null)->comment('ΑΔΑ λύσης σύμβασης'));
        $this->addColumn('{{%stplacement_teacher}}', 'cancelled', $this->boolean()->notNull()->defaultValue(false)->comment('Ανάκληση απόφασης πρόσληψης'));
        $this->addColumn('{{%stplacement_teacher}}', 'cancelled_at', $this->timestamp()->null()->defaultValue(null));
        $this->addColumn('{{%stplacement_teacher}}', 'cancelled_ada', $this->string(200)->null()->defaultValue(null)->comment('ΑΔΑ ανάκλησης'));
        $this->addColumn('{{%stplacement_teacher}}', 'contract_start_date', $this->date()->null()->defaultValue(null)->comment('Έναρξη σύμβασης'));
        $this->addColumn('{{%stplacement_teacher}}', 'contract_end_date', $this->date()->null()->defaultValue(null)->comment('Λήξη σύμβασης'));
        $this->addColumn('{{%stplacement_teacher}}', 'service_start_date', $this->date()->null()->defaultValue(null)->comment('Ανάληψη υπηρεσίας'));
        $this->addColumn('{{%stplacement_teacher}}', 'service_end_date', $this->date()->null()->defaultValue(null)->comment('Λήξη υπηρεσίας/απόλυση/οικ.αποχώρηση'));
    }

    public function safeDown()
    {
        $this->allowInvalidDates();

        $this->dropColumn('{{%stplacement}}', 'ada');
        $this->dropColumn('{{%stplacement}}', 'base_contract_start_date');
        $this->dropColumn('{{%stplacement}}', 'base_contract_end_date');

        $this->dropColumn('{{%stplacement_teacher}}', 'dismissed_ada');
        $this->dropColumn('{{%stplacement_teacher}}', 'cancelled');
        $this->dropColumn('{{%stplacement_teacher}}', 'cancelled_at');
        $this->dropColumn('{{%stplacement_teacher}}', 'cancelled_ada');
        $this->dropColumn('{{%stplacement_teacher}}', 'contract_start_date');
        $this->dropColumn('{{%stplacement_teacher}}', 'contract_end_date');
        $this->dropColumn('{{%stplacement_teacher}}', 'service_start_date');
        $this->dropColumn('{{%stplacement_teacher}}', 'service_end_date');
    }
}
