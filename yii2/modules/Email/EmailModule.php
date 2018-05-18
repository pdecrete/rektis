<?php

namespace app\modules\Email;

use yii\base\Module;

class EmailModule extends Module
{
    public function init()
    {
        parent::init();

        \Yii::configure($this, require(__DIR__ . '/config/params.php'));
    }
}
