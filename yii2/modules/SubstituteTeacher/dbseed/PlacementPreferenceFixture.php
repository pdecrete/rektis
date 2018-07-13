<?php

namespace app\modules\SubstituteTeacher\dbseed;

use yii\test\ActiveFixture;
use app\modules\SubstituteTeacher\models\Teacher;

class PlacementPreferenceFixture extends ActiveFixture
{
    public $tableName = '{{%stplacement_preference}}';
    public $depends = [
        // 'app\modules\SubstituteTeacher\dbseed\PrefectureFixture', // comment this if you do not run prefectures fixtures 
        'app\modules\SubstituteTeacher\dbseed\TeacherFixture',
    ];

    protected function getData()
    {
        $teachers = Teacher::find()->all();

        $data = [];

        $prefecture_ids = [1, 2, 3, 4];

        foreach ($teachers as $model) {
            shuffle($prefecture_ids);
            $how_many_choices = rand(1,4);
            $separate_choices = (rand(1,100) > 75); // ~75% chance of no separate choices
            $order = 1;
            for ($i = 0; $i < $how_many_choices; $i++) {
                if ($separate_choices === true) {
                    $data[] = [
                        'prefecture_id' => $prefecture_ids[$i],
                        'teacher_id' => $model->id,
                        'school_type' => 1,
                        'order' => $order++
                    ];
                    $data[] = [
                        'prefecture_id' => $prefecture_ids[$i],
                        'teacher_id' => $model->id,
                        'school_type' => 2,
                        'order' => $order++
                    ];
                } else {
                    $data[] = [
                        'prefecture_id' => $prefecture_ids[$i],
                        'teacher_id' => $model->id,
                        'school_type' => 0,
                        'order' => $order++
                    ];
                }
            }
        }

        return $data;
    }
}
