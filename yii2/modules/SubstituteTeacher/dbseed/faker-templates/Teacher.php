<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
use app\modules\SubstituteTeacher\models\TeacherRegistry;

// $registry_ids = TeacherRegistry::find()->select('id')->asArray()->all();
// print_r($registry_ids);
// die();

return [
    'registry_id' => $index + 1, // assumes that teacher registry also started at index 0
    'year' => 2018, 
    'data' => '{}'
];
