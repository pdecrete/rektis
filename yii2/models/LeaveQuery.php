<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Leave]].
 *
 * @see Leave
 */
class LeaveQuery extends \yii\db\ActiveQuery
{
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return Leave[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Leave|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function deleted($deleted = true)
    {
        return $this->andWhere(['deleted' => $deleted]);
    }
}
