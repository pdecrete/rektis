<?php

namespace app\modules\SubstituteTeacher\models;

/**
 * This is the ActiveQuery class for [[CallTeacherSpecialisation]].
 *
 * @see CallTeacherSpecialisation
 */
class CallTeacherSpecialisationQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return CallTeacherSpecialisation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CallTeacherSpecialisation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
