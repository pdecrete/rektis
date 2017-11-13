<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Specialisation]].
 *
 * @see Specialisation
 */
class SpecialisationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Specialisation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Specialisation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
