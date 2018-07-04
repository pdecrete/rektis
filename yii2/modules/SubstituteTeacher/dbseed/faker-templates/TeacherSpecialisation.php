<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
use app\modules\SubstituteTeacher\models\Specialisation;

$pe2300id = \Yii::$app->cache->getOrSet('pe2300id', function () {
    $pe2300_model = Specialisation::findOne(['code' => 'ΠΕ 2300']);
    return $pe2300_model->id;
});

$pe2500id = \Yii::$app->cache->getOrSet('pe2500id', function () {
    $pe2500_model = Specialisation::findOne(['code' => 'ΠΕ 2500']);
    return $pe2500_model->id;
});

$ebpid = \Yii::$app->cache->getOrSet('ebpid', function () {
    $ebpid_model = Specialisation::findOne(['code' => 'ΕΒΠ']);
    return $ebpid_model->id;
});

return [
    'registry_id' => $index + 1, // assumes that teacher registry also started at index 0
    'specialisation_id' => $faker->randomElement([$pe2300id, $pe2500id, $ebpid])
];
