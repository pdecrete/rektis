<?php

namespace app\modules\finance\models;

/**
 * This is the ActiveQuery class for [[FinanceKaecreditpercentage]].
 *
 * @see FinanceKaecreditpercentage
 */
class FinanceKaecreditpercentageQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return FinanceKaecreditpercentage[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return FinanceKaecreditpercentage|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
