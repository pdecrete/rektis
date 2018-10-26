<?php
namespace app\modules\disposal\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use app\modules\disposal\DisposalModule;
use app\modules\disposal\models\Statistic;
use app\modules\eduinventory\components\EduinventoryHelper;

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
        $school_years = Statistic::getSchoolYearOptions();
        if(is_null($school_years)){
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', "The are no disposals to show statistics."));
            return $this->redirect(['disposal/index']);
        }
        //$prefectures = EduinventoryHelper::getPrefectureOptions();
        //echo "<pre>"; print_r($prefectures); echo "</pre>"; 
        
        $educationlevels = EduinventoryHelper::getEducationalLevelOptions();
        echo "<pre>"; print_r($educationlevels); echo "</pre>"; die();
    }
    
    public function actionExportstatistic()
    {
    
    }
    
    public function actionExportexcel($period)
    {
        
    }
    
    
}

