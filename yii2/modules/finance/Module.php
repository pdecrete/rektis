<?php

namespace app\modules\finance;

use Yii;
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
                'except' => ['/finance/finance-year/*']
            ],
        ];
    }

    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/finance/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'forceTranslation' => true,
            'sourceLanguage' => 'en-US',
            'basePath' => '@app/modules/finance/messages',
            'fileMap' => [
                'modules/finance/app' => 'modules/finance/app.php'
            ],
        ];
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        //echo "<pre>"; print_r(Yii::$app->i18n->getMessageSource($category)); echo "</pre>"; die();
        //echo Yii::$app->i18n->getMessageSource($category)
        $message = Yii::t($category, $message, $params, 'el-GR');
        if(Yii::$app->session["working_year"] >= 2019)
            $message = str_replace('ΚΑΕ', 'Λογαριασμoύ', $message);

        return $message;
    }
}
