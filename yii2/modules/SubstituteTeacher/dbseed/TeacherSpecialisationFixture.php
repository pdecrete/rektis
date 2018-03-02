<?php

namespace app\modules\SubstituteTeacher\dbseed;

use yii\test\ActiveFixture;

class TeacherSpecialisationFixture extends ActiveFixture
{
    public $tableName = '{{%stteacher_registry_specialisation}}';
    public $depends = [
        'app\modules\SubstituteTeacher\dbseed\TeacherRegistryFixture',
        // also depends on specialisations, but...
    ];

    protected function getData()
    {
        $pe2300 = 126;
        $pe2500 = 128;
        $ebp = 190;

        return [
            ['registry_id' => 1, 'specialisation_id' => $pe2300],
            ['registry_id' => 1, 'specialisation_id' => $pe2500],
            ['registry_id' => 2, 'specialisation_id' => $pe2300],
            ['registry_id' => 3, 'specialisation_id' => $ebp],
            ['registry_id' => 4, 'specialisation_id' => $ebp],
        ];
    }
}
