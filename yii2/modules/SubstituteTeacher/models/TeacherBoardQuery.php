<?php

namespace app\modules\SubstituteTeacher\models;

/**
 * This is the ActiveQuery class for [[TeacherBoard]].
 *
 * @see TeacherBoard
 */
class TeacherBoardQuery extends \yii\db\ActiveQuery
{

    public function status($status)
    {
        return $this->andWhere(['status' => $status]);
    }

    /**
     * @inheritdoc
     * @return TeacherBoard[]|array
     */
    public function all($db = null)
    {
        $this->with(['specialisation']);
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
