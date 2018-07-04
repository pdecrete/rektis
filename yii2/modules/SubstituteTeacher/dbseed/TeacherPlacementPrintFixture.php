<?php

namespace app\modules\SubstituteTeacher\dbseed;

use yii\test\ActiveFixture;

class TeacherPlacementPrintFixture extends ActiveFixture
{
    public $tableName = '{{%stplacement_print}}';
    public $depends = [
        'app\modules\SubstituteTeacher\dbseed\TeacherPlacementFixture',
    ];

    protected function getData()
    {
        return [
        ];
    }
}
