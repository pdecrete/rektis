<?php

namespace app\modules\finance\models;

/**
 * This is the ActiveQuery class for [[KAE]].
 *
 * @see KAE
 */
class KAEQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return KAE[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return KAE|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
