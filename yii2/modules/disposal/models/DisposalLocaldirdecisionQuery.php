<?php

namespace app\modules\disposal\models;

/**
 * This is the ActiveQuery class for [[DisposalLocaldirdecision]].
 *
 * @see DisposalLocaldirdecision
 */
class DisposalLocaldirdecisionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return DisposalLocaldirdecision[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return DisposalLocaldirdecision|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
