<?php

namespace app\modules\disposal\models;

/**
 * This is the ActiveQuery class for [[DisposalDisposalapproval]].
 *
 * @see DisposalDisposalapproval
 */
class DisposalDisposalapprovalQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return DisposalDisposalapproval[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return DisposalDisposalapproval|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
