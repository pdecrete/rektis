<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Position]].
 *
 * @see Position
 */
class AdmappPositionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

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