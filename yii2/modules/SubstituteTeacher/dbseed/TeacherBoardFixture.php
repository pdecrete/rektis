<?php

namespace app\modules\SubstituteTeacher\dbseed;

use yii\test\ActiveFixture;

class TeacherBoardFixture extends ActiveFixture
{
    public $tableName = '{{%stteacher_board}}';
    public $depends = [
        'app\modules\SubstituteTeacher\dbseed\TeacherFixture',
    ];

    protected function getData()
    {
        $pe2300 = 126;
        $pe2500 = 128;
        $ebp = 190;

        return [
            ['specialisation_id' => $pe2300, 'teacher_id' => 1, 'board_type' => 1, 'points' => 100, 'order' => 1],
            ['specialisation_id' => $pe2300, 'teacher_id' => 2, 'board_type' => 1, 'points' => 90, 'order' => 2],
            ['specialisation_id' => $pe2300, 'teacher_id' => 3, 'board_type' => 1, 'points' => 80, 'order' => 3],
            ['specialisation_id' => $pe2300, 'teacher_id' => 4, 'board_type' => 1, 'points' => 70, 'order' => 4],
            ['specialisation_id' => $pe2300, 'teacher_id' => 5, 'board_type' => 1, 'points' => 60, 'order' => 5],
            ['specialisation_id' => $pe2300, 'teacher_id' => 6, 'board_type' => 1, 'points' => 50, 'order' => 6],
            ['specialisation_id' => $pe2300, 'teacher_id' => 7, 'board_type' => 1, 'points' => 40, 'order' => 7],
            ['specialisation_id' => $pe2300, 'teacher_id' => 8, 'board_type' => 1, 'points' => 30, 'order' => 8],
            ['specialisation_id' => $pe2300, 'teacher_id' => 9, 'board_type' => 1, 'points' => 20, 'order' => 9],
            ['specialisation_id' => $pe2300, 'teacher_id' => 10, 'board_type' => 1, 'points' => 10, 'order' => 10],
            ['specialisation_id' => $pe2500, 'teacher_id' => 1, 'board_type' => 2, 'points' => 50, 'order' => 2],
        ];
    }
}
