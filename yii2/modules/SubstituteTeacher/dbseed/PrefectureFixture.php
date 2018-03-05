<?php

namespace app\modules\SubstituteTeacher\dbseed;

use yii\test\ActiveFixture;

class PrefectureFixture extends ActiveFixture
{
    public $tableName = '{{%stprefecture}}';

    protected function getData()
    {
        return [
            ['region' => 'ΚΡΗΤΗΣ', 'prefecture' => 'ΗΡΑΚΛΕΙΟΥ', 'symbol' => 'Η'],
            ['region' => 'ΚΡΗΤΗΣ', 'prefecture' => 'ΛΑΣΙΘΙΟΥ', 'symbol' => 'Λ'],
            ['region' => 'ΚΡΗΤΗΣ', 'prefecture' => 'ΡΕΘΥΜΝΟΥ', 'symbol' => 'Ρ'],
            ['region' => 'ΚΡΗΤΗΣ', 'prefecture' => 'ΧΑΝΙΩΝ', 'symbol' => 'Χ'],
        ];
    }
}
