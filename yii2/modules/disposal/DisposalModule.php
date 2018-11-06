<?php

namespace app\modules\disposal;

use Yii;
use app\models\HeadSignature;

/**
 * disposal module definition class
 */
class DisposalModule extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\disposal\controllers';

    public function init()
    {
        parent::init();
        \Yii::configure($this, require __DIR__ . '/config/params.php');
        $this->registerTranslations();
        if(!isset(Yii::$app->session[$this->id . "_whosigns"])){           
            Yii::$app->session->set($this->id . "_whosigns", HeadSignature::DIRECTOR_SIGN);
        }
    }
    
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/disposal/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'forceTranslation' => true,
            'sourceLanguage' => 'en-US',
            'basePath' => '@app/modules/disposal/messages',
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