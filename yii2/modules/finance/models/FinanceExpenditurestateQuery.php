<?php

namespace app\modules\finance\models;

/**
 * This is the ActiveQuery class for [[FinanceExpenditurestate]].
 *
 * @see FinanceExpenditurestate
 */
class FinanceExpenditurestateQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return FinanceExpenditurestate[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return FinanceExpenditurestate|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
