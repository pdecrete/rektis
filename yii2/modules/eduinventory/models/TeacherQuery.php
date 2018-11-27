<?php

namespace app\modules\eduinventory\models;

/**
 * This is the ActiveQuery class for [[Teacher]].
 *
 * @see Teacher
 */
class TeacherQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Teacher[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Teacher|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
