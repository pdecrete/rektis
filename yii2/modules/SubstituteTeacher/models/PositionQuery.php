<?php

namespace app\modules\SubstituteTeacher\models;

/**
 * This is the ActiveQuery class for [[Position]].
 *
 * @see Position
 */
class PositionQuery extends \yii\db\ActiveQuery
{

    /**
     * @inheritdoc
     * @return Position[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Position|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
