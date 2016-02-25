<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[AuthRule]].
 *
 * @see AuthRule
 */
class AuthRuleQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return AuthRule[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AuthRule|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}