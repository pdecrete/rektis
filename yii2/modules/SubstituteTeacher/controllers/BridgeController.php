<?php

namespace app\modules\SubstituteTeacher\controllers;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\httpclient\Client;

class BridgeController extends \yii\web\Controller
{
    private $client = null; // http client for calls to the applications frontend

    public function init()
    {
        $this->client = new Client([
            'baseUrl' => $this->module->params['applications-baseurl'],
            // 'transport' => 'yii\httpclient\CurlTransport',
            // 'timeout' => 20,
            'requestConfig' => [
                'format' => Client::FORMAT_JSON
            ],
            'responseConfig' => [
                'format' => Client::FORMAT_JSON
            ],
        ]);
    }

    protected function getHeaders()
    {
        return [
            'Authorization' => "Bearer " . $this->module->params['applications-key']
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'remote-status' => ['GET', 'POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['remote-status', 'receive', 'send'],
                        'allow' => true,
                        'roles' => ['admin', 'spedu_user'],
                    ],
                ],
            ],
        ];
    }

    public function actionRemoteStatus()
    {
        $data = null;
        $status = null; 
        $services_status = [
            'applications' => false,
            'load' => false,
            'clear' => false,
            'unload' => false,
        ];

        if (\Yii::$app->request->isPost) {
            $status_response = $this->client->post('index', null, $this->getHeaders())->send();
            $status = $status_response->isOk ? $status_response->isOk : $status_response->statusCode; 
            $data = $status_response->getData();            
            $services_status = array_merge($services_status, $data['services']);
        }
        return $this->render('remote-status', compact('status', 'services_status', 'data'));
    }

    public function actionReceive()
    {
        return $this->render('receive');
    }

    public function actionSend()
    {
        return $this->render('send');
    }
}