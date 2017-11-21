<?php

namespace app\modules\finance\controllers;

use yii\web\Controller;

/**
 * Default controller for the `finance` module
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
    
    public function actionAdministeryear()
    {
        return $this->render('administeryear');
    }
    
    public function actionParameterize()
    {
        return $this->render('parameterize');
    }
}
