<?php
namespace app\assets;

class SweetAlertAsset extends \yii\web\AssetBundle
{

    // composer require bower-asset/sweetalert:1.1.*

    public $sourcePath = '@bower/sweetalert/dist';
    public $css = [
        'sweetalert.css',
    ];
    public $js = [
        'sweetalert.min.js'
    ];

}
