<?php

namespace app\modules\schooltransport\models;

/**
 * This is the ActiveQuery class for [[SchtransportTransport]].
 *
 * @see SchtransportTransport
 */
class SchtransportTransportQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return SchtransportTransport[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SchtransportTransport|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
