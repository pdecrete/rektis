<?php
namespace app\modules\finance\components;
use app\modules\finance\Module;
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
            Yii::$app->session->setFlash('danger', Module::t('modules/finance/app', "Error in defining the financial year as currently working. Check if currently working year is correctly defined or contact with the administrator."));

            if(!(Yii::$app->controller->id == 'finance-year'))
            {
                return Yii::$app->response->redirect(['/finance/finance-year']);
            }
            return true;
        }
        else
            Yii::$app->session["working_year"] = $workingYear;
        
        if(!Integrity::creditsIntegrity(Yii::$app->session["working_year"]))
        {
            Yii::$app->session->setFlash('danger', Module::t('modules/finance/app', "The sum of RCN credits is not equal to the credit of financial year {year}. Please correct to continue.", ['year' => Yii::$app->session["working_year"]]));
            
            if(!(Yii::$app->controller->id == 'finance-kaecredit')){
                return Yii::$app->response->redirect(['/finance/finance-kaecredit']);
            }
        }      
        return true;
    }
}