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
        \Yii::configure($this, require __DIR__ . '/config/params.php');
        $this->registerTranslations();
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
        return Yii::t($category, $message, $params, 'el-GR');
    }
}
