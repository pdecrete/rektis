<?php
namespace app\widgets\HeadSignature;

use yii\base\Widget;
use app\models\HeadSignature;
use app\modules\disposal\DisposalModule;
use Yii;

class HeadSignatureWidget extends Widget
{    
    public $form;
    public $model;
    
    public function init()
    {
        parent::init();
        $this->model = new HeadSignature();
        $this->model->who_signs = Yii::$app->session[DisposalModule::className() . "_whosigns"];
    }
    
    public function run()
    {        
        $head_signs = HeadSignature::getSignatureOptions();
        return $this->render('headsignature_selection', ['head_signs' => $head_signs, 'form' => $this->form, 'model' => $this->model]);
    }
}