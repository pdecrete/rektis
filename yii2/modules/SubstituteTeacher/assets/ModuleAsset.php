<?php
namespace app\modules\SubstituteTeacher\assets;

class ModuleAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '';
    public $css = [
    ];
    public $js = [
        'substitute-teacher.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'app\assets\SweetAlertAsset', // TODO: break app dependency!
    ];

    public function init()
    {
        parent::init();
        $this->sourcePath = __DIR__ . '/files';
    }
}
