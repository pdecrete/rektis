<?php
namespace app\modules\SubstituteTeacher\traits;

use yii\helpers\ArrayHelper;

/**
 * Provided an active record class, get a list of choices suitable for
 * selections on forms.
 * Default fields: id, label
 * If class has properties named id and label, nothing else need to be done for basinc functionality
 */
trait Selectable
{

    /**
     * Get a list of available choices in the form of
     * ID => LABEL suitable for select lists.
     *
     * @param $index_property string
     * @param $label_property string
     * @param $group_property string|null
     */
    public static function selectables($index_property = 'id', $label_property = 'label', $group_property = null)
    {
        $active_query = (get_called_class())::find();
        // TODO, add support for active query conditions

        return ArrayHelper::map($active_query->all(), $index_property, $label_property, $group_property);
    }
}
