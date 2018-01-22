<?php

namespace app\modules\SubstituteTeacher\dbseed;

use yii\test\ActiveFixture;

class PrefectureFixture extends ActiveFixture
{
    public $tableName = '{{%stprefecture}}';

    protected function getData()
    {
        return [
            ['region' => 'ΚΡΗΤΗΣ', 'prefecture' => 'ΗΡΑΚΛΕΙΟΥ'],
            ['region' => 'ΚΡΗΤΗΣ', 'prefecture' => 'ΛΑΣΙΘΙΟΥ'],
            ['region' => 'ΚΡΗΤΗΣ', 'prefecture' => 'ΡΕΘΥΜΝΟΥ'],
            ['region' => 'ΚΡΗΤΗΣ', 'prefecture' => 'ΧΑΝΙΩΝ'],
        ];
    }
}
