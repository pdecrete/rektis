<?php

namespace app\modules\schooltransport\models;

/**
 * This is the ActiveQuery class for [[SchtransportProgramcategory]].
 *
 * @see SchtransportProgramcategory
 */
class SchtransportProgramcategoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return SchtransportProgramcategory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SchtransportProgramcategory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
