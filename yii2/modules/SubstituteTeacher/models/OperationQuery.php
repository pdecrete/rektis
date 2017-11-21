<?php
namespace app\modules\SubstituteTeacher\models;

/**
 * This is the ActiveQuery class for [[Operation]].
 *
 * @see Operation
 */
class OperationQuery extends \yii\db\ActiveQuery
{

    /**
     * @inheritdoc
     * @return Operation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Operation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
