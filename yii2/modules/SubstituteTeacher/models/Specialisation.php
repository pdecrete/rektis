<?php

namespace app\modules\SubstituteTeacher\models;

use yii\helpers\ArrayHelper;

/**
 * @inheritdoc
 */
class Specialisation extends \app\models\Specialisation
{
    /**
     * Have to redefine in order to catch same namespace model
     * @inheritdoc
     */
    public static function selectables()
    {
        $choices_aq = new SpecialisationQuery(get_called_class());

        return ArrayHelper::map($choices_aq->all(), 'id', 'label');
    }

    /**
     * Have to redefine in order to catch same namespace model
     * @inheritdoc
     */
    public static function find()
    {
        return new SpecialisationQuery(get_called_class());
    }
}
