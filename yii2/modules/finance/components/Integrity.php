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
     * Returns the number of RCNs stored in the database.
     * @return number|string
     */
    public static function kaesCount()
    {
        return FinanceKae::find()->count();
    }
    
    /**
     * Returns the number of RCNs that have been set for the currently, working year. 
     * If the working year has not been set then -1 is returned.
     * @return number
     */
    public static function currentYearKaesCount()
    {
        if(is_null(Yii::$app->session["working_year"]) || Yii::$app->session["working_year"] == "") 
            return -1;
        else 
            return FinanceKaecredit::find()->where(['year' => Yii::$app->session["working_year"]])->count();
    }
    
    /** TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO  
     * Checks whether the initial credit for the year equals to the sum
     * of the credits of all the RCNs (KAEs) of the year.
     * @param integer $year
     * @throws \Exception
     */
    
    public static function creditsIntegrity(){
        
        try {            
            echo $yearKaesCount; die();
            $_year = intval($year);
            if(!is_int($_year)) throw new \Exception();
            $queryYearCredit = (new \yii\db\Query())->select('year_credit')
                                                    ->from('admapp_finance_year')
                                                    ->where(['year'=>$year])->all();
            $yearCredit = $queryYearCredit[0]['year_credit'];
//            $yearCredit = $queryKaes->sum('kaecredit_amount');
            $queryKaes = (new \yii\db\Query())->select('kaecredit_amount')
                                              ->from('admapp_finance_kaecredit');
            $creditsSum = $queryKaes->sum('kaecredit_amount');
            echo "<pre>"; print_r($queryKaes); echo "</pre>";
//            echo $creditsSum;
            die();
        }
        catch(\Exception $exc){
            Yii::$app->session->setFlash('danger', "Σφάλμα κατά τον έλεγχο ακεραιότητας της βάσης δεδομένων.");
            echo "Exception";
            die();
            Yii::$app->response->redirect(['/finance']);
            
        }
    }
}