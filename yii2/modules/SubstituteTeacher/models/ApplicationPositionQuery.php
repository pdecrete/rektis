<?php

namespace app\modules\SubstituteTeacher\models;

/**
 * This is the ActiveQuery class for [[ApplicationPosition]].
 *
 * @see ApplicationPosition
 */
class ApplicationPositionQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return ApplicationPosition[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ApplicationPosition|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
