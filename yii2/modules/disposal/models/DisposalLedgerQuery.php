<?php

namespace app\modules\disposal\models;

/**
 * This is the ActiveQuery class for [[DisposalLedger]].
 *
 * @see DisposalLedger
 */
class DisposalLedgerQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return DisposalLedger[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return DisposalLedger|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
