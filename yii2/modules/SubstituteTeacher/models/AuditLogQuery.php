<?php

namespace app\modules\SubstituteTeacher\models;

/**
 * This is the ActiveQuery class for [[AuditLog]].
 *
 * @see AuditLog
 */
class AuditLogQuery extends \yii\db\ActiveQuery
{
    /**
     *
     * @param int $from unix timestamp
     * @param int $to unix timestamp
     * @return \yii\db\ActiveQuery
     */
    public function inDateRange($from, $to)
    {
        return $this->andWhere(['between', 'log_time', $from, $to]);
    }
}
