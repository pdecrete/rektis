<?php

namespace app\modules\SubstituteTeacher\models;

/**
 * This is the ActiveQuery class for [[TeacherBoard]].
 *
 * @see TeacherBoard
 */
class TeacherBoardQuery extends \yii\db\ActiveQuery
{

    /**
     * @inheritdoc
     * @return TeacherBoard[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TeacherBoard|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
