<?php

namespace app\modules\eduinventory\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Default controller for the `eduinventory` module
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
                    ['actions' => ['index'], 'allow' => true, 'roles' => ['eduinventory_viewer']],                    
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
}
