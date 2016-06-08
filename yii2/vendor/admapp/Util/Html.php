<?php

namespace admapp\Util;

use Yii;
use yii\web\View;
use yii\helpers\Html as yiiHtml;
use yii\bootstrap\BaseHtml as bootstrapHtml;

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
            $attribute1 = $attributename[1];
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

    /**
     * Generate button to copy value from one form field to another. 
     * Example usage: 
     * <pre>
     * admapp\Util\Html::displayCopyFieldValueButton($model, 'date1', 'date2', 'Copy from date1 to date2', null, '-disp');
     * </pre>
     * 
     * @param Object $model The model containing the attributes to work with
     * @param string $attr_from The attribute to get value of
     * @param string $attr_to The attribute to copy value to 
     * @param string $label Text to accompany the button icon 
     * @param string $classes Optional classes to use for the button (default: btn btn-sm btn-default)
     * @param string $also_update_fields_with_suffix For use is the fields have accompanied fields (i.e. by DateControl); 
     * the value of this param is appended to the id of the fields 
     * @return type
     */
    public static function displayCopyFieldValueButton($model, $attr_from, $attr_to, $label = null, $classes = null, $also_update_fields_with_suffix = null)
    {
        if (!is_string($label)) {
            $label = '';
        }
        if (!is_string($classes)) {
            $classes = 'btn btn-sm btn-default';
        }
        $mid = 'cpfvbtn-' . $attr_from . '-' . $attr_to;
        if (is_string($also_update_fields_with_suffix)) {
            $extra_update_js = '$("#' . yiiHtml::getInputId($model, $attr_to) . $also_update_fields_with_suffix . '").val($("#' . yiiHtml::getInputId($model, $attr_from) . $also_update_fields_with_suffix . '").val());';
        } else {
            $extra_update_js = '';
        }
        $js = '
            $("#' . $mid . '").on("click", function() { 
                $("#' . yiiHtml::getInputId($model, $attr_to) . '").val($("#' . yiiHtml::getInputId($model, $attr_from) . '").val()); ' . $extra_update_js . '
            });
';
        Yii::$app->controller->view->registerJs($js, View::POS_READY, $mid);
        return yiiHtml::button(bootstrapHtml::icon('paste') . (($label != '') ? ' ' . $label : ''), ['id' => $mid, 'class' => $classes]);
    }

}
