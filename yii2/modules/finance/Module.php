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
                'except' => ['/finance/finance-year']
            ],
        ];
    }
    
    public function init()
    {
        parent::init();
        $this->registerTranslations();
        //\Yii::configure($this, require __DIR__ . '/config/config.php');
    }
    
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/finance/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@app/modules/finance/messages',
            'fileMap' => [
                'modules/finance/validation' => 'validation.php',
                'modules/finance/form' => 'form.php',
                
            ],
        ];
    }
    
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/finance/' . $category, $message, $params, $language);
    }
}
