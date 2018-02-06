<?php

namespace app\modules\finance\models;

/**
 * This is the ActiveQuery class for [[FinanceExpenddeduction]].
 *
 * @see FinanceExpenddeduction
 */
class FinanceExpenddeductionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return FinanceExpenddeduction[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return FinanceExpenddeduction|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
