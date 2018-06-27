<?php

namespace app\modules\SubstituteTeacher\models;

/**
 * This is the ActiveQuery class for [[Prefecture]].
 *
 * @see Prefecture
 */
class PrefectureQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return Prefecture[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Prefecture|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
