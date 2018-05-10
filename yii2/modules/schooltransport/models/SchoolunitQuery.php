<?php

namespace app\modules\schooltransport\models;

/**
 * This is the ActiveQuery class for [[Schoolunit]].
 *
 * @see Schoolunit
 */
class SchoolunitQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Schoolunit[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Schoolunit|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
