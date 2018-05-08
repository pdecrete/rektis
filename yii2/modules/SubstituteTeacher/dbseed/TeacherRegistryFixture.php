<?php

namespace app\modules\SubstituteTeacher\dbseed;

use Yii;
use yii\test\ActiveFixture;

class TeacherRegistryFixture extends ActiveFixture
{
    public $tableName = '{{%stteacher_registry}}';
    public $dataFile = __DIR__ . '/data/TeacherRegistry.php';

    public function init()
    {
        parent::init();
        Yii::$app->db->createCommand("SET SQL_MODE='ALLOW_INVALID_DATES'")->execute();
    }

}
