<?php

namespace app\modules\finance\models;

/**
 * This is the ActiveQuery class for [[FinanceKaewithdrawal]].
 *
 * @see FinanceKaewithdrawal
 */
class FinanceKaewithdrawalQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return FinanceKaewithdrawal[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return FinanceKaewithdrawal|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
