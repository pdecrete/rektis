<?php

namespace app\modules\schooltransport\models;

/**
 * This is the ActiveQuery class for [[SchtransportProgram]].
 *
 * @see SchtransportProgram
 */
class SchtransportProgramQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return SchtransportProgram[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SchtransportProgram|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
