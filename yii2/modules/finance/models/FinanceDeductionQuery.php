<?php

namespace app\modules\finance\models;

/**
 * This is the ActiveQuery class for [[FinanceDeduction]].
 *
 * @see FinanceDeduction
 */
class FinanceDeductionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return FinanceDeduction[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return FinanceDeduction|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
