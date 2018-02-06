<?php

namespace app\modules\finance\models;

/**
 * This is the ActiveQuery class for [[FinanceKae]].
 *
 * @see FinanceKae
 */
class FinanceKaeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return FinanceKae[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return FinanceKae|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
