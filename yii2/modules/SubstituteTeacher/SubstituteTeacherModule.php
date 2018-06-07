<?php
namespace app\modules\SubstituteTeacher;

use Yii;
use yii\base\Module;
use yii\base\BootstrapInterface;

class SubstituteTeacherModule extends Module implements BootstrapInterface
{
    public function init()
    {
        parent::init();
        \Yii::setAlias('@upload', __DIR__ . '/_upload');

        $this->registerTranslations();

        $this->params['foo'] = 'bar';
        \Yii::configure($this, require(__DIR__ . '/config/params.php'));

        // set log target for module
        \Yii::$app->log->targets[] = Yii::createObject(require(__DIR__ . '/config/log.php'));

        \Yii::$container->setSingleton('Crypt', [
            'class' => 'app\modules\SubstituteTeacher\components\Crypt',
            'cryptKeyFile' => $this->params['crypt-key-file']
        ]);
    }

    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'app\modules\SubstituteTeacher\commands';
        }
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['substituteteacher'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@app/modules/SubstituteTeacher/messages',
            'fileMap' => [
                'substituteteacher' => 'substituteteacher.php',
            ],
        ];
    }
}
