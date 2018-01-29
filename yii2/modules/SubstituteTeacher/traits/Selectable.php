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
     * @param $callable modify active query for custom filtering, ordering etc. MUST BE AN INLINE FUNCTION
     *  The function will receive one parameter, the ActiveQuery object.
     */
    public static function selectables($index_property = 'id', $label_property = 'label', $group_property = null, $callable = null)
    {
        $active_query = call_user_func(get_called_class() . '::find');

        if (is_callable($callable)) {
            $active_query = $callable($active_query);
        }

        return ArrayHelper::map($active_query->all(), $index_property, $label_property, $group_property);
    }

    /**
     * Provides a default selectables method
     */
    public static function defaultSelectables($index_property = 'id', $label_property = 'label', $group_property = null)
    {
        return static::selectables($index_property, $label_property, $group_property, null);
    }
}
