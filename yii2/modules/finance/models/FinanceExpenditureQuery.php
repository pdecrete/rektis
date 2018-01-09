<?php

namespace app\modules\finance\models;

/**
 * This is the ActiveQuery class for [[FinanceExpenditure]].
 *
 * @see FinanceExpenditure
 */
class FinanceExpenditureQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return FinanceExpenditure[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return FinanceExpenditure|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
