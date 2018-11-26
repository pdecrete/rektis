<?php
namespace app\modules\disposal\widgets;

use yii\base\Widget;

class ButtonShortcuts extends Widget
{
    public $rejected;
    public $archived;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('buttons_shortcuts');
    }
}
