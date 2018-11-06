<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

class HeadSignatureController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [   'class' => AccessControl::className(),
                'rules' =>  [
                    ['actions' => ['signatureajax'], 'allow' => true, 'roles' => ['visitor']], /*TODO*/
                ]
            ]
        ];
    }
    
    public function actionSignatureajax()
    {
        $data = 0;
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $whosigns = Yii::$app->request->post('who_signs');
            $module = Yii::$app->request->post('working_module');
            Yii::$app->session->set($module . "_whosigns", $whosigns);
            $data = 1; //Success
        }
        return $data;
    }
}