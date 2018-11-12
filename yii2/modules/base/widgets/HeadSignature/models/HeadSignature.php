<?php
namespace app\modules\base\widgets\HeadSignature\models;

use yii\base\Model;

class HeadSignature extends Model
{
    const DIRECTOR_SIGN = 1;
    const DEPUTY_DIRECTOR_SIGN = 2;
    
    public $who_signs;
    
    public static function getSignatureOptions() 
    {
        return [self::DIRECTOR_SIGN => \Yii::$app->params['director'],
                self::DEPUTY_DIRECTOR_SIGN => \Yii::$app->params['deputy_director']
               ];
    }
    
    public static function getSignatureTitleOptions()
    {
        return [self::DIRECTOR_SIGN => \Yii::$app->params['director_sign'],
                self::DEPUTY_DIRECTOR_SIGN => \Yii::$app->params['deputy_director_sign']
               ];
    }
    
    public static function getSigningName($signingcode)
    {
        if($signingcode == -1)
            $signingcode = 1;
        return self::getSignatureOptions()[$signingcode];
    }
    
    public static function getSigningTitle($signingcode)
    {
        if($signingcode == -1)
            $signingcode = 1;
        return self::getSignatureTitleOptions()[$signingcode];
    }
}

