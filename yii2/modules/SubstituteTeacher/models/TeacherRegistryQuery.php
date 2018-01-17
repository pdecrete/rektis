<?php

namespace app\modules\SubstituteTeacher\models;

/**
 * This is the ActiveQuery class for [[TeacherRegistry]].
 *
 * @see TeacherRegistry
 */
class TeacherRegistryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TeacherRegistry[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TeacherRegistry|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
