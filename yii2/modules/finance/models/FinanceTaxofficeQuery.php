<?php

namespace app\modules\finance\models;

/**
 * This is the ActiveQuery class for [[FinanceTaxoffice]].
 *
 * @see FinanceTaxoffice
 */
class FinanceTaxofficeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return FinanceTaxoffice[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return FinanceTaxoffice|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
