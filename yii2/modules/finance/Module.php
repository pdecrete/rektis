<?php

namespace app\modules\finance;
use app\modules\finance\models\FinanceYear;
use yii\base\ErrorException;

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
        $workingyear = FinanceYear::find()->where(['year_iscurrent'=>1])->asArray()->all();
        if(count($workingyear) != 1)
            throw new ErrorException("\"Working Year\" is not correclty set.");
        else
            \Yii::$app->session["working_year"] = $workingyear[0]['year'];
        
        //\Yii::configure($this, require __DIR__ . '/config/config.php');
    }
}
