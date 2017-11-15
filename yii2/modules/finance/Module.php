<?php

namespace app\modules\finance;

use Yii;

/**
 * finance module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\finance\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        //\Yii::configure($this, require __DIR__ . '/config/config.php');
    }
}
