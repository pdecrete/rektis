<?php
namespace app\modules\finance\components;
use Yii;
use yii\base\ActionFilter;
use app\modules\finance\models\FinanceYear;
use yii\base\ErrorException;


class FinanceInitialChecks extends ActionFilter
{
    public function beforeAction($action)
    {
        $parentBeforeAction = parent::beforeAction($action);

        if (!$parentBeforeAction) {
            return false;
        }

        $workingyear = FinanceYear::find()->where(['year_iscurrent'=>1])->asArray()->all();
        
        if(count($workingyear) != 1)
        {              
            if(!(Yii::$app->controller->id == 'finance-year'))    
                Yii::$app->response->redirect(['/finance/finance-year']);
             
            Yii::$app->session->setFlash('info', "Δεν έχει οριστεί το οικονομικό έτος στο οποίο εργάζεστε. Παρακαλώ ορίστε ένα έτος ως \"Τρέχον\".");
        }
        else
        {
            Yii::$app->session["working_year"] = $workingyear[0]['year'];
            //Yii::$app->controller->renderPartial('/default/infopanel');
        }
        
        return true;
    }
}
