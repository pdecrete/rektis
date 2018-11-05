<?php
namespace app\models;

use yii\base\Model;

class HeadSignature extends Model
{
    const DIRECTOR_SIGN = 1;
    const DEPUTY_DIRECTOR_SIGN = 2;
    
    public $who_signs;
    
    public static function getSignatureOptions() {
        return [ self::DIRECTOR_SIGN => \Yii::$app->params['director'],
                        self::DEPUTY_DIRECTOR_SIGN => \Yii::$app->params['deputy_director']];
        
    }
}

