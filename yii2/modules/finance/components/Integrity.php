<?php
namespace app\modules\finance\components;

use Yii;

class Integrity
{
    /* Checks whether the initial credit for the year equals to the sum
     * of the credits of all the RCNs (KAEs) of the year.
     */
    
    public static function creditsIntegrity($year){
        try {
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