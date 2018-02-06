<?php

namespace app\modules\finance\models;

/**
 * This is the ActiveQuery class for [[FinanceFpa]].
 *
 * @see FinanceFpa
 */
class FinanceFpaQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return FinanceFpa[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return FinanceFpa|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
