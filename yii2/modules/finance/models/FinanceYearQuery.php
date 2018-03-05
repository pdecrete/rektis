<?php

namespace app\modules\finance\models;

/**
 * This is the ActiveQuery class for [[FinanceYear]].
 *
 * @see FinanceYear
 */
class FinanceYearQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return FinanceYear[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return FinanceYear|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
