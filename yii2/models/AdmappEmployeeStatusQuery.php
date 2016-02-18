<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[EmployeeStatus]].
 *
 * @see EmployeeStatus
 */
class AdmappEmployeeStatusQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return EmployeeStatus[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return EmployeeStatus|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}