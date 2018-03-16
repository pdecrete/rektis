<?php

namespace app\modules\schooltransport;

use Yii;

/**
 * schooltransport module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\schooltransport\controllers';

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
        Yii::$app->i18n->translations['modules/schooltransport/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'forceTranslation' => true,
            'sourceLanguage' => 'en-US',
            'basePath' => '@app/modules/schooltransport/messages',
            'fileMap' => [
                'modules/schooltransport/app' => 'modules/schooltransport/app.php'
            ],
        ];
    }
    
    public static function t($category, $message, $params = [], $language = null)
    {
        //echo "<pre>"; print_r(Yii::$app->i18n->getMessageSource($category)); echo "</pre>"; die();
        //echo Yii::$app->i18n->getMessageSource($category)
        return Yii::t($category, $message, $params, 'el-GR');
    }
}
