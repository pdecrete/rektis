<?php

namespace admapp\Util;

use Yii;

class Html
{

    /**
     * Generate form-like field to display info
     * 
     * @param Object $model the model class
     * @param string|array $attributename the attribute name (label is displayed in first column, value in second)
     *      if array is provided, first item is for the label, second is for the value
     * @param int $c1w first column width
     * @param int $c2w second column width
     * @return string 
     */
    public static function displayValueOfField($model, $attributename, $c1w = 2, $c2w = 10)
    {
        Yii::$app->controller->view->registerCss('.form-group .well { margin-bottom: 0; }', [], 'wellforform');
        if (is_array($attributename)) {
            $attribute0 = $attributename[0];
            $attribute1= $attributename[1];
        } else {
            $attribute0 = $attributename;
            $attribute1 = $attributename;
        }
        return <<< EOTMPLINFORMATIONDISPLAYLINE
            <div class="form-group">
                <div class="col-sm-{$c1w} control-label">{$model->getAttributeLabel($attribute0)}</div>
                <div class="col-sm-{$c2w}"><div class="well well-sm">{$model->$attribute1}</div>
                </div>
            </div>
EOTMPLINFORMATIONDISPLAYLINE;
    }

}
