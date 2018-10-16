<?php

namespace app\modules\eduinventory;

use Yii;

/**
 * eduinventory module definition class
 */
class EducationInventoryModule extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\eduinventory\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
        // custom initialization code goes here
    }
    
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/eduinventory/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'forceTranslation' => true,
            'sourceLanguage' => 'en-US',
            'basePath' => '@app/modules/eduinventory/messages',
            'fileMap' => [
                'app' => 'app.php'
            ],
        ];
    }
    
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t($category, $message, $params, 'el-GR');
    }
}
