<?php

namespace app\modules\finance;
use app\modules\finance\components\FinanceInitialChecks;

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
    
    public function behaviors()
    {
        return [
            [
                'class' => FinanceInitialChecks::className(),
                'except' => ['/finance/finance-year']
            ],
        ];
    }
    
    public function init()
    {
        parent::init();

        
        //\Yii::configure($this, require __DIR__ . '/config/config.php');
    }
}
