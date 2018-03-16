<?php

namespace app\modules\schooltransport\models;

/**
 * This is the ActiveQuery class for [[Directorate]].
 *
 * @see Directorate
 */
class DirectorateQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Directorate[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Directorate|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
