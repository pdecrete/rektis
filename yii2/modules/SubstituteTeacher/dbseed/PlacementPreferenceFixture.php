<?php

namespace app\modules\SubstituteTeacher\dbseed;

use yii\test\ActiveFixture;

class PlacementPreferenceFixture extends ActiveFixture
{
    public $tableName = '{{%stplacement_preference}}';
    public $depends = [
        'app\modules\SubstituteTeacher\dbseed\PrefectureFixture', // comment this if you do not run prefectures fixtures 
        'app\modules\SubstituteTeacher\dbseed\TeacherFixture',
    ];

    protected function getData()
    {
        $data = [
            ['prefecture_id' => 1, 'teacher_id' => 1, 'school_type' => 0, 'order' => 1],
            ['prefecture_id' => 2, 'teacher_id' => 1, 'school_type' => 0, 'order' => 2],
            ['prefecture_id' => 3, 'teacher_id' => 1, 'school_type' => 0, 'order' => 3],
            ['prefecture_id' => 4, 'teacher_id' => 1, 'school_type' => 0, 'order' => 4],
        ];

        for ($i = 1; $i <= 3; $i++) {
            $step = ($i - 1) * 3;
            $data = array_merge($data, [
                ['prefecture_id' => 1, 'teacher_id' => $step + 2, 'school_type' => 2, 'order' => 1],
                ['prefecture_id' => 1, 'teacher_id' => $step + 2, 'school_type' => 1, 'order' => 2],
                ['prefecture_id' => 3, 'teacher_id' => $step + 2, 'school_type' => 2, 'order' => 3],
                ['prefecture_id' => 3, 'teacher_id' => $step + 2, 'school_type' => 1, 'order' => 4],
                ['prefecture_id' => 3, 'teacher_id' => $step + 3, 'school_type' => 0, 'order' => 1],
                ['prefecture_id' => 4, 'teacher_id' => $step + 3, 'school_type' => 0, 'order' => 2],
                ['prefecture_id' => 1, 'teacher_id' => $step + 4, 'school_type' => 2, 'order' => 1],
                ['prefecture_id' => 2, 'teacher_id' => $step + 4, 'school_type' => 2, 'order' => 2],
                ['prefecture_id' => 3, 'teacher_id' => $step + 4, 'school_type' => 2, 'order' => 3],
                ['prefecture_id' => 4, 'teacher_id' => $step + 4, 'school_type' => 2, 'order' => 4],
            ]);
        }

        return $data;
    }
}
