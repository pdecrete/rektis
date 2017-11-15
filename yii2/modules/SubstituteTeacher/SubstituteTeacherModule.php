<?php
namespace app\modules\SubstituteTeacher;

use yii\base\Module;

class SubstituteTeacherModule extends Module
{

    public function init()
    {
        parent::init();
        \Yii::setAlias('@upload', __DIR__ . '/_upload');

//        \Yii::configure($this, require(__DIR__ . '/config/params.php'));
    }
}
