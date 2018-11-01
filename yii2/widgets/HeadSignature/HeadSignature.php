<?php
namespace app\widgets\HeadSignature;

use yii\base\Widget;

class HeadSignature extends Widget
{
    public function init()
    {
        parent::init();
    }
    
    public function run()
    {
        $head_signs = [ 'headoftheHead' => \Yii::$app->params['director'],
                        'finSign' => \Yii::$app->params['FinanceDepartmentHead'],
                        'ictSign' => \Yii::$app->params['ICTDepartmentHead'],
                        'admSign' => \Yii::$app->params['AdministrationDepartmentHead'],
                        'lawSign' => \Yii::$app->params['LawDepartmentHead'],
        ];
        return $this->render('headsignature_selection', ['head_signs' => $head_signs]);
    }
}