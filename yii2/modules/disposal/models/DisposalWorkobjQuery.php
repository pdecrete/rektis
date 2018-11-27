<?php

namespace app\modules\disposal\models;

/**
 * This is the ActiveQuery class for [[DisposalWorkobj]].
 *
 * @see DisposalWorkobj
 */
class DisposalWorkobjQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return DisposalWorkobj[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return DisposalWorkobj|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
