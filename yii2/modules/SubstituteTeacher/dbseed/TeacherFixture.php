<?php

namespace app\modules\SubstituteTeacher\dbseed;

use yii\test\ActiveFixture;

class TeacherFixture extends ActiveFixture
{
    public $tableName = '{{%stteacher}}';
    public $depends = [
        'app\modules\SubstituteTeacher\dbseed\TeacherRegistryFixture'
    ];

    protected function getData()
    {
        return [
            ['registry_id' => 1, 'year' => 2016, 'status' => 1, 'points' => 88.00, 'data' => '{}' ],
            ['registry_id' => 1, 'year' => 2017, 'status' => 0, 'points' => 88.00, 'data' => '{}' ],
            ['registry_id' => 2, 'year' => 2016, 'status' => 0, 'points' => 90.00, 'data' => '{}' ],
            ['registry_id' => 2, 'year' => 2017, 'status' => 0, 'points' => 90.00, 'data' => '{}' ],
            ['registry_id' => 3, 'year' => 2017, 'status' => 0, 'points' => 77.00, 'data' => '{}' ],
            ['registry_id' => 4, 'year' => 2017, 'status' => 0, 'points' => 85.00, 'data' => '{}' ],
        ];
    }
}
