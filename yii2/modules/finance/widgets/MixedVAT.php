<?php
namespace app\modules\finance\widgets;

use yii\base\Widget;

class MixedVAT extends Widget
{

    public function init()
    {
        parent::init();        
    }
    
    public function run()
    {
        return $this->render('mixedVAT_calculator');
    }
}

