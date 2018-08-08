<?php

namespace app\modules\SubstituteTeacher\dbseed;

use yii\test\ActiveFixture;
use app\modules\SubstituteTeacher\models\Specialisation;

class TeacherSpecialisationFixture extends ActiveFixture
{
    public $tableName = '{{%stteacher_registry_specialisation}}';
    public $depends = [
        'app\modules\SubstituteTeacher\dbseed\TeacherRegistryFixture',
        // also depends on specialisations, but...
    ];
    public $dataFile = __DIR__ . '/data/TeacherSpecialisation.php';

    // Obsolete; refactor if needed to assign multiple specialisations
    //
    // protected function getData()
    // {
    //     $pe2300_model = Specialisation::findOne(['code' => 'ΠΕ 2300']);
    //     $pe2500_model = Specialisation::findOne(['code' => 'ΠΕ 2500']);
    //     $ebp_model = Specialisation::findOne(['code' => 'ΔΕ1']);
    //     if (empty($pe2300_model) || empty($pe2500_model) || empty($ebp_model)) {
    //         throw new \Exception('One of the required specialisations was not found.');
    //     }

    //     $pe2300id = $pe2300_model->id;
    //     $pe2500id = $pe2500_model->id;
    //     $ebpid = $ebp_model->id;

    //     return [
    //         ['registry_id' => 1, 'specialisation_id' => $pe2300id],
    //         ['registry_id' => 2, 'specialisation_id' => $pe2300id],
    //         ['registry_id' => 3, 'specialisation_id' => $ebpid],
    //         ['registry_id' => 4, 'specialisation_id' => $ebpid],
    //         ['registry_id' => 5, 'specialisation_id' => $pe2300id],
    //         ['registry_id' => 6, 'specialisation_id' => $pe2300id],
    //         ['registry_id' => 7, 'specialisation_id' => $pe2300id],
    //         ['registry_id' => 8, 'specialisation_id' => $pe2300id],
    //         ['registry_id' => 9, 'specialisation_id' => $pe2300id],
    //         ['registry_id' => 10, 'specialisation_id' => $pe2300id],

    //         ['registry_id' => 1, 'specialisation_id' => $pe2500id],
    //         ['registry_id' => 5, 'specialisation_id' => $ebpid],
    //     ];
    // }
}
