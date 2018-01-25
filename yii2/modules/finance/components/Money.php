<?php
namespace app\modules\finance\components;

use Yii;

/**
 * The internal representation of money and percentages values is in integer.
 * Money amounts are stored in cents and percentages as integers that comes out
 * from the multiplication of the percentage value with 100. Money class acts as
 * a bridge to transform the internal representation to what users expect i.e. amounts
 * in euros and percentages.
 * 
 * @author jhaniot
 */
class Money
{    
    public static function toCents($amount) {
        return intval(round($amount*100));
    }
    
    public static function toCurrency($amount, $formatted = false){
        if($formatted) 
            return Yii::$app->formatter->asCurrency($amount/100);        
        return $amount/100;        
    }
    
    public static function toPercentage($dbPercentage, $formatted = true) {
        if($formatted == false)
            return round($dbPercentage/100, 4);
        return Yii::$app->formatter->asPercent(round($dbPercentage/10000, 4), 2);
    }
    
    public static function toDbPercentage($percentage) {   
        return intval(round(str_replace(',', '.', str_replace('%', '', $percentage))*100));; 
    }
}