<?php

namespace app\modules\SubstituteTeacher\models;

/**
 * This is the ActiveQuery class for [[Teacher]].
 *
 * @see Teacher
 */
class TeacherQuery extends \yii\db\ActiveQuery
{
    public function year($year)
    {
        return $this->andWhere(['year' => $year]);
    }

    /**
     * @inheritdoc
     * @return Teacher[]|array
     */
    public function all($db = null)
    {
        $this->with(['registry', 'boards']);
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
