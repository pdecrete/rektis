<?php

namespace app\modules\finance\models;

/**
 * This is the ActiveQuery class for [[FinanceKaecredit]].
 *
 * @see FinanceKaecredit
 */
class FinanceKaecreditQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return FinanceKaecredit[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return FinanceKaecredit|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
