<?php
namespace app\modules\finance\components;
use Yii;
use yii\base\ActionFilter;
//use app\modules\finance\models\FinanceYear;


class FinanceInitialChecks extends ActionFilter
{
    public function beforeAction($action)
    {
        $parentBeforeAction = parent::beforeAction($action);

        if (!$parentBeforeAction) {
            return false;
        }
        
        if(!($workingYear = Integrity::uniqueCurrentYear()))
        {   
            if(!(Yii::$app->controller->id == 'finance-year'))
                Yii::$app->response->redirect(['/finance/finance-year']);
                
            Yii::$app->session->setFlash('info', "Σφάλμα στον ορισμό του οικονομικού έτους στο οποίο εργάζεστε. Παρακαλώ επικοινωνήστε με το διαχειριστή.");
        }
        else
        {
            Yii::$app->session["working_year"] = $workingYear;
            //Yii::$app->controller->renderPartial('/default/infopanel');
        }
        
        return true;
    }
}
