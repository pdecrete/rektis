<?php

namespace app\modules\SubstituteTeacher\models;

/**
 * This is the ActiveQuery class for [[CallPosition]].
 *
 * @see CallPosition
 */
class CallPositionQuery extends \yii\db\ActiveQuery
{

    public function ofCall($call_id) 
    {
        return $this->andWhere(['[[call_id]]' => $call_id]);
    }

    public function ofGroup($group) 
    {
        return $this->andWhere(['[[group]]' => $group]);
    }

    /**
     * @inheritdoc
     * @return CallPosition[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CallPosition|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
