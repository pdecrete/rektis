<?php
namespace yii2\controllers;

use Yii;
use yii\base\Response;
use yii\filters\AccessControl;
use yii\web\Controller;

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
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $whosigns = Yii::$app->request->post('whosigns');
            
        }
    }
}