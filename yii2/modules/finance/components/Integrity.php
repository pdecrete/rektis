<?php
namespace app\modules\finance\components;

use Yii;
use app\modules\finance\models\FinanceYear;
use app\modules\finance\models\FinanceKae;
use app\modules\finance\models\FinanceKaecredit;

class Integrity
{
    /**
     * Returns the currently working year if there is only one financial year set as currently working, 
     * otherwise if there are more than one years set as currently working, it returns false.
     * @param integer $year
     * @return boolean|number
     */
    public static function uniqueCurrentYear(){
        $currentYearsNum = FinanceYear::find()->where(['year_iscurrent' => 1]);
        if(!($currentYearsNum->count() == 1))
            return false;
        return $currentYearsNum->one()->year;
    }
    
    /**
     * Returns true if $year is set as currently working year, otherwise false.
     * @param integer $year
     * @return boolean
     */
    public static function isCurrent($year){
        $model = FinanceYear::find()->where(['year' => $year])->one();
        if(is_null($model) || $model->year_iscurrent == 0)
            return false;
        else
            return true;
    }

    /**
     * Returns true if $year is locked, otherwise false.
     * @param integer $year
     * @return boolean
     */
    public static function isLocked($year){
        $model = FinanceYear::find()->where(['year' => $year])->one();
        if(is_null($model) || $model->year_lock == 0)
            return false;
        else
            return true;
    }
    
    
    /**
     * Returns the number of RCNs that have been set for the currently, working year. 
     * If the working year has not been set then -1 is returned.
     * @return number
     */
    private static function yearKaesCount($year){
        return FinanceKaecredit::find()->where(['year' => $year])->count();
    }
    
//    private
    
//    private static function kaesCredits($year){
                
//    }
    
    
    /** TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO  
     * Checks whether the initial credit for the year equals to the sum
     * of the credits of all the RCNs (KAEs) of the year.
     * @param integer $year
     * @throws \Exception
     */  
   
    public static function creditsIntegrity($year){
        if(Integrity::yearKaesCount($year) == 0) 
            return true;
        
        if(FinanceKae::find()->count() != Integrity::yearKaesCount($year)) 
            return false;
        
        $yearCredit = FinanceYear::getYearCredit($year);        
        $creditsSum = FinanceKaecredit::getSumKaeCredits($year);
        
        if($yearCredit != $creditsSum) 
            return false;

        return true;        
    }
}