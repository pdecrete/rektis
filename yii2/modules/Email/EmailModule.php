<?php

namespace app\modules\Email;

use Yii;
use yii\base\Module;

class EmailModule extends Module
{
    public function init()
    {
        parent::init();

        Yii::configure($this, require(__DIR__ . '/config/params.php'));

        // set log target for module; expects 2 dim.array
        foreach (require(__DIR__ . '/config/log.php') as $target) {
            Yii::$app->log->targets[] = Yii::createObject($target);
        }
    }
}
