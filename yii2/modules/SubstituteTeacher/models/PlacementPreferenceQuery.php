<?php

namespace app\modules\SubstituteTeacher\models;

/**
 * This is the ActiveQuery class for [[PlacementPreference]].
 *
 * @see PlacementPreference
 */
class PlacementPreferenceQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return PlacementPreference[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PlacementPreference|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
