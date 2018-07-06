<?php

namespace app\modules\SubstituteTeacher\dbseed;

use yii\test\ActiveFixture;
use app\modules\SubstituteTeacher\models\Teacher;
use app\modules\SubstituteTeacher\models\Specialisation;
use Faker\Factory as FakerFactory;

class TeacherBoardFixture extends ActiveFixture
{
    public $tableName = '{{%stteacher_board}}';
    public $depends = [
        'app\modules\SubstituteTeacher\dbseed\TeacherFixture',
        'app\modules\SubstituteTeacher\dbseed\TeacherSpecialisationFixture'
    ];

    protected function getData()
    {
        $faker = FakerFactory::create();

        // get the teachers with one specialisation
        $teachers = Teacher::find()->all();

        $data = [];
        $grouping = [];
        $data = array_map(function ($model) use ($faker, &$grouping) {
            // get first specialisation for teacher board
            // and do some kind of sorting
            $specialisations = $model->registry->teacherRegistrySpecialisations;
            $board_type = ($faker->boolean(60) ? 1 : 2);
            $specialisation_id = empty($specialisations) ? null : $specialisations[0]->specialisation_id;
            $grouping_key = "{$board_type}_{$specialisation_id}";
            if (array_key_exists($grouping_key, $grouping)) {
                $grouping[$grouping_key]['order']++;
                $grouping[$grouping_key]['points'] += $faker->numberBetween(1, 10);
            } else {
                $grouping[$grouping_key] = [
                    'order' => 1,
                    'points' => $faker->numberBetween(10, 30),
                ];
            }
            return [
                'specialisation_id' => $specialisation_id,
                'teacher_id' => $model->id,
                'board_type' => $board_type,
                'points' => $grouping[$grouping_key]['points'],
                'order' => $grouping[$grouping_key]['order'],
                'status' => Teacher::TEACHER_STATUS_ELIGIBLE
            ];
        }, $teachers);

        // consider assigning teachers with second specialisation

        return $data;
    }
}
