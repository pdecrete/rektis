<?php

namespace app\modules\finance\models;

/**
 * This is the ActiveQuery class for [[FinanceInvoicetype]].
 *
 * @see FinanceInvoicetype
 */
class FinanceInvoicetypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return FinanceInvoicetype[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return FinanceInvoicetype|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
