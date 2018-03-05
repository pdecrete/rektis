<?php

namespace app\modules\SubstituteTeacher\models;

/**
 * This is the ActiveQuery class for [[TeacherStatusAudit]].
 *
 * @see TeacherStatusAudit
 */
class TeacherStatusAuditQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TeacherStatusAudit[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TeacherStatusAudit|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
