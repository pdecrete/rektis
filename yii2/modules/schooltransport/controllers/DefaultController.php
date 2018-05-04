<?php

namespace app\modules\schooltransport\controllers;

use app\modules\schooltransport\Module;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * Default controller for the `schooltransport` module
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [   'class' => AccessControl::className(),
                'rules' =>  [
                    ['actions' => ['index', 'help', 'legislation'], 'allow' => true, 'roles' => ['schtransport_viewer']],                    
                    ['allow' => true, 'roles' => ['schtransport_director']]
                ]
            ]
        ];
    }
    
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionHelp($helpId)
    {
        return $this->render('help', ['helpId' => $helpId]);
    }
    
    public function actionLegislation($fileId)
    {
        $files = [  1 => '6202_4218_2017_metak_exoteriko.pdf', 2 => 'fek_2769_2011.pdf', 3 => 'fek_681_Β_2017.pdf',
                    4 => 'P.D._79_2017_fek_109_01_08_2017.pdf', 5 => 'F.12_FM_53243_D1_02_04_2018_METAKINHSEIS_MATHITON_DIMOTIKON_VOULI_ERGASTHRI_DIMOKRATIAS.pdf',
                    6 => 'F.12_FM_48140_D1_21-03-2017_Y.A.pdf'
        ];
        
        if(!array_key_exists($fileId, $files)){
            Yii::$app->session->addFlash('danger', Module::t('modules/schooltransport/app', "Error in downloading file."));
            return $this->redirect(['help']);
        }
            
            
        $filedownload = Yii::getAlias("@vendor/admapp/resources/schooltransports/legislation/" . $files[$fileId]);           
        return Yii::$app->response->SendFile($filedownload);
    }
}
