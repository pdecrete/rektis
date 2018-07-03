<?php

namespace app\modules\SubstituteTeacher\models;

/**
 * This is the ActiveQuery class for [[PlacementPrint]].
 *
 * @see PlacementPrint
 */
class PlacementPrintQuery extends \yii\db\ActiveQuery
{

    /**
     * @inheritdoc
     * @return PlacementPrint[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PlacementPrint|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
