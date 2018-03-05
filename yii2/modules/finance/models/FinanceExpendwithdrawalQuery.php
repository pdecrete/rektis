<?php

namespace app\modules\finance\models;

/**
 * This is the ActiveQuery class for [[FinanceExpendwithdrawal]].
 *
 * @see FinanceExpendwithdrawal
 */
class FinanceExpendwithdrawalQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return FinanceExpendwithdrawal[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return FinanceExpendwithdrawal|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
