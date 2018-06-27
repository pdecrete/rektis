<?php

namespace app\modules\SubstituteTeacher\models;

/**
 * This is the ActiveQuery class for [[PlacementPosition]].
 *
 * @see PlacementPosition
 */
class PlacementPositionQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return PlacementPosition[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PlacementPosition|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
