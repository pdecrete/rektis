<?php
namespace app\modules\SubstituteTeacher\models;

/**
 * This is the ActiveQuery class for [[SubstituteTeacherFile]].
 *
 * @see SubstituteTeacherFile
 */
class SubstituteTeacherFileQuery extends \yii\db\ActiveQuery
{

    public function active()
    {
        return $this->andWhere(['[[deleted]]' => SubstituteTeacherFile::FILE_ACTIVE]);
    }

    /**
     * @inheritdoc
     * @return SubstituteTeacherFile[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SubstituteTeacherFile|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
