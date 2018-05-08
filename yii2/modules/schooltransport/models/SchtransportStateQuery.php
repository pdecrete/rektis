<?php

namespace app\modules\schooltransport\models;

/**
 * This is the ActiveQuery class for [[SchtransportState]].
 *
 * @see SchtransportState
 */
class SchtransportStateQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return SchtransportState[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SchtransportState|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
