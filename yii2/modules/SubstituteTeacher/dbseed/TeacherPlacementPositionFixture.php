<?php

namespace app\modules\SubstituteTeacher\dbseed;

use yii\test\ActiveFixture;

class TeacherPlacementPositionFixture extends ActiveFixture
{
    public $tableName = '{{%stplacement_position}}';
    public $depends = [
        'app\modules\SubstituteTeacher\dbseed\TeacherPlacementFixture',
    ];

    protected function getData()
    {
        return [
        ];
    }
}
