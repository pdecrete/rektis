<?php
namespace app\modules\disposal\conrollers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class StatisticController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [   'class' => AccessControl::className(),
                'rules' =>  [
                    ['actions' => ['index', 'exportstatistic', 'exportexcel'], 'allow' => true, 'roles' => ['schtransport_viewer']],
                ]
            ]
        ];
    }
    
    
    public function beforeAction($action)
    {
        if($action->id == 'exportstatistic')
            $this->enableCsrfValidation = false;
            return parent::beforeAction($action);
    }
    
    
    public function actionIndex()
    {
        
    }
    
    public function actionExportstatistic()
    {
    
    }
    
    public function actionExportexcel($period)
    {
        
    }
    
    
}

