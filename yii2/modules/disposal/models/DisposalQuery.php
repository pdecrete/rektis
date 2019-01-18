<?php

namespace app\modules\disposal\models;

/**
 * This is the ActiveQuery class for [[Disposal]].
 *
 * @see Disposal
 */
class DisposalQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Disposal[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Disposal|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
