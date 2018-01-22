<?php

namespace app\modules\SubstituteTeacher\dbseed;

use yii\test\ActiveFixture;

class PlacementPreferenceFixture extends ActiveFixture
{

    public $tableName = '{{%stplacement_preference}}';
    public $depends = [
        // 'app\modules\SubstituteTeacher\dbseed\PrefectureFixture', // TODO enable when full seeding is available 
        'app\modules\SubstituteTeacher\dbseed\TeacherFixture',
    ];

    protected function getData()
    {
        return [
            ['prefecture_id' => 1, 'teacher_id' => 1, 'school_type' => null, 'order' => 1],
            ['prefecture_id' => 2, 'teacher_id' => 1, 'school_type' => null, 'order' => 2],
            ['prefecture_id' => 3, 'teacher_id' => 1, 'school_type' => null, 'order' => 3],
            ['prefecture_id' => 4, 'teacher_id' => 1, 'school_type' => null, 'order' => 4],
            ['prefecture_id' => 1, 'teacher_id' => 2, 'school_type' => 1, 'order' => 1],
            ['prefecture_id' => 1, 'teacher_id' => 2, 'school_type' => 0, 'order' => 2],
            ['prefecture_id' => 3, 'teacher_id' => 2, 'school_type' => 1, 'order' => 3],
            ['prefecture_id' => 3, 'teacher_id' => 2, 'school_type' => 0, 'order' => 4],
            ['prefecture_id' => 3, 'teacher_id' => 3, 'school_type' => null, 'order' => 1],
            ['prefecture_id' => 4, 'teacher_id' => 3, 'school_type' => null, 'order' => 2],
            ['prefecture_id' => 1, 'teacher_id' => 4, 'school_type' => 1, 'order' => 1],
            ['prefecture_id' => 2, 'teacher_id' => 4, 'school_type' => 1, 'order' => 2],
            ['prefecture_id' => 3, 'teacher_id' => 4, 'school_type' => 1, 'order' => 3],
            ['prefecture_id' => 4, 'teacher_id' => 4, 'school_type' => 1, 'order' => 4],
        ];
    }
}
