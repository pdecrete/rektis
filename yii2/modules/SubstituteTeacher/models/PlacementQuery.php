<?php

namespace app\modules\SubstituteTeacher\models;

/**
 * This is the ActiveQuery class for [[Placement]].
 *
 * @see Placement
 */
class PlacementQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return Placement[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Placement|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
