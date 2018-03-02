<?php

namespace app\modules\finance\models;

/**
 * This is the ActiveQuery class for [[FinanceInvoice]].
 *
 * @see FinanceInvoice
 */
class FinanceInvoiceQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return FinanceInvoice[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return FinanceInvoice|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
