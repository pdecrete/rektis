<?php
namespace app\modules\SubstituteTeacher\assets;

class ModuleAsset extends \yii\web\AssetBundle
{

    public $sourcePath = __DIR__ . '/files';
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

}
