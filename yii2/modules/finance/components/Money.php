<?php
namespace app\modules\finance\components;

class Money
{
    public static function toCents($amount) {
        return intval(round($amount*100));
    }
    
    public static function toCurrency($amount){
        return round($amount/100, 2);
    }
}