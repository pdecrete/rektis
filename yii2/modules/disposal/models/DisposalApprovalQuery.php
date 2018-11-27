<?php

namespace app\modules\disposal\models;

/**
 * This is the ActiveQuery class for [[DisposalApproval]].
 *
 * @see DisposalApproval
 */
class DisposalApprovalQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return DisposalApproval[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return DisposalApproval|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
