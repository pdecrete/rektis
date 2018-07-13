<?php

namespace app\modules\SubstituteTeacher\dbseed;

use yii\test\ActiveFixture;

class TeacherFixture extends ActiveFixture
{
    public $tableName = '{{%stteacher}}';
    public $depends = [
        'app\modules\SubstituteTeacher\dbseed\TeacherRegistryFixture'
    ];
    public $dataFile = __DIR__ . '/data/Teacher.php';

    protected function getData()
    {
        return array_merge(require $this->dataFile, [
            ['registry_id' => 1, 'year' => 2017, 'data' => '{}' ],
            ['registry_id' => 2, 'year' => 2017, 'data' => '{}' ],
            ['registry_id' => 3, 'year' => 2017, 'data' => '{}' ],
            ['registry_id' => 4, 'year' => 2017, 'data' => '{}' ],
            ['registry_id' => 5, 'year' => 2017, 'data' => '{}' ],
            ['registry_id' => 6, 'year' => 2017, 'data' => '{}' ],
            ['registry_id' => 7, 'year' => 2017, 'data' => '{}' ],
            ['registry_id' => 8, 'year' => 2017, 'data' => '{}' ],
            ['registry_id' => 9, 'year' => 2017, 'data' => '{}' ],
            ['registry_id' => 10, 'year' => 2017, 'data' => '{}' ],
            ['registry_id' => 1, 'year' => 2016, 'data' => '{}' ],
            ['registry_id' => 2, 'year' => 2016, 'data' => '{}' ],
        ]);
    }
}
