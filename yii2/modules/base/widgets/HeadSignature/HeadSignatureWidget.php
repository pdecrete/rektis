<?php
namespace app\modules\base\widgets\HeadSignature;

use yii\base\Widget;
use app\models\HeadSignature;
use Yii;

class HeadSignatureWidget extends Widget
{    
    public $form;
    public $model;
    public $module;
    
    public function init()
    {
        parent::init();
        $this->model = new HeadSignature();
        $this->model->who_signs = Yii::$app->session[$this->module . "_whosigns"];
    }
    
    public function run()
    {
        $head_signs = HeadSignature::getSignatureOptions();
        return $this->render('headsignature_selection', ['head_signs' => $head_signs, 'form' => $this->form, 'model' => $this->model, 'module' => $this->module]);
    }
}