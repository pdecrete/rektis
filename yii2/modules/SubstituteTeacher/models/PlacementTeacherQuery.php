<?php

namespace app\modules\SubstituteTeacher\models;

/**
 * This is the ActiveQuery class for [[PlacementTeacher]].
 *
 * @see PlacementTeacher
 */
class PlacementTeacherQuery extends \yii\db\ActiveQuery
{

    /**
     * @inheritdoc
     * @return PlacementTeacher[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PlacementTeacher|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
