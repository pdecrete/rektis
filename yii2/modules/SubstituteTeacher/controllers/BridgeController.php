<?php

namespace app\modules\SubstituteTeacher\controllers;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\httpclient\Client;
use app\modules\SubstituteTeacher\models\Prefecture;
use app\modules\SubstituteTeacher\models\CallPosition;
use app\modules\SubstituteTeacher\models\Teacher;

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

    /**
     * Choose a call and send relevant data to applications frontend.
     *
     * TODO: param int call_id for the selection of positions
     * TODO: param int year for the selection of teachers OR add year to call!
     *      REAL SCENARIO : The operator will select the teachers that should be applicable!!!
     */
    public function actionSend()
    {
        $call_id = 2;
        $year = 2017;

        // collect positions, prefectures, teachers and placement preferences
        $prefectures = Prefecture::find()->all();
        $prefecture_substitutions = array_map(function ($m) {
            return $m->id;
        }, $prefectures);
        $prefectures = array_map(function ($m) {
            return $m->toApiJson();
        }, $prefectures);

        $call_positions = CallPosition::findOnePreGroup($call_id);
        $call_positions = array_map(function ($m) use ($prefecture_substitutions) {
            return $m->toApiJson($prefecture_substitutions);
        }, $call_positions);

        $teachers = Teacher::find()
            ->year($year)
            ->status(Teacher::TEACHER_STATUS_ELIGIBLE)
            ->joinWith(['registry', 'registry.specialisations'])
            ->all();
        $placement_preferences = [];
        $walk = array_walk($teachers, function ($m, $k) use (&$placement_preferences) {
            $placement_preferences = array_merge($placement_preferences, $m->placementPreferences);
        });
        $teachers = array_map(function ($m) {
            return $m->toApiJson();
        }, $teachers);
        $placement_preferences = array_map(function ($m) {
            return $m->toApiJson();
        }, $placement_preferences);

        echo "<pre>", print_r($placement_preferences, true), "</pre>";
        echo "<pre>", print_r($teachers, true), "</pre>";
        echo "<pre>", print_r($call_positions, true), "</pre>";
        echo "<pre>", print_r($prefectures, true), "</pre>";
        die();

        return $this->render('send');
    }
}
