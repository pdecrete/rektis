<?php

namespace app\modules\schooltransport\controllers;

use yii\web\Controller;

/**
 * Default controller for the `schooltransport` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionHelp()
    {
        return $this->render('help');
    }
}
