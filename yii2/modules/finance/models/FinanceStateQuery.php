<?php

namespace app\modules\finance\models;

/**
 * This is the ActiveQuery class for [[FinanceState]].
 *
 * @see FinanceState
 */
class FinanceStateQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return FinanceState[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return FinanceState|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
