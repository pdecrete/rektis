<?php

namespace app\modules\schooltransport\models;

/**
 * This is the ActiveQuery class for [[SchtransportMeeting]].
 *
 * @see SchtransportMeeting
 */
class SchtransportMeetingQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return SchtransportMeeting[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SchtransportMeeting|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
