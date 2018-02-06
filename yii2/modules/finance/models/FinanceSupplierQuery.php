<?php

namespace app\modules\finance\models;

/**
 * This is the ActiveQuery class for [[FinanceSupplier]].
 *
 * @see FinanceSupplier
 */
class FinanceSupplierQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return FinanceSupplier[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return FinanceSupplier|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
