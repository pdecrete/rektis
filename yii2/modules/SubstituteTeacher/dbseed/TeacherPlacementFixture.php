<?php

namespace app\modules\SubstituteTeacher\dbseed;

use yii\test\ActiveFixture;

class TeacherPlacementFixture extends ActiveFixture
{
    public $tableName = '{{%stplacement_teacher}}';
    public $depends = [
        'app\modules\SubstituteTeacher\dbseed\TeacherBoardFixture',
    ];

    protected function getData()
    {
        return [
        ];
    }
}
