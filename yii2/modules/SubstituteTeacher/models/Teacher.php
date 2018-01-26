<?php

namespace app\modules\SubstituteTeacher\models;

use Yii;
use app\modules\SubstituteTeacher\traits\Selectable;
use app\modules\SubstituteTeacher\models\Prefecture;

/**
 * This is the model class for table "{{%stteacher}}".
 *
 * @property integer $id
 * @property integer $registry_id
 * @property integer $year
 * @property integer $status
 * @property string $points
 * 
 * @property string $name
 *
 * @property PlacementPreference[] $placementPreferences
 * @property TeacherRegistry $registry
 * @property TeacherStatusAudit[] $teacherStatusAudits
 * @property Prefecture[] $placementPreferencePrefectures
 */
class Teacher extends \yii\db\ActiveRecord
{
    use Selectable;

    const TEACHER_STATUS_ELIGIBLE = 0;
    const TEACHER_STATUS_APPOINTED = 1;
    const TEACHER_STATUS_NEGATION = 2;

    public $status_label;
    public $name;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stteacher}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['registry_id', 'year', 'status'], 'integer'],
            [['points'], 'default', 'value' => 0],
            [['registry_id', 'year', 'status'], 'required'],
            [['points'], 'number'],
            [['year', 'registry_id'], 'unique', 'targetAttribute' => ['year', 'registry_id'], 'message' => 'The combination of Registry ID and Year has already been taken.'],
            [['registry_id'], 'exist', 'skipOnError' => true, 'targetClass' => TeacherRegistry::className(), 'targetAttribute' => ['registry_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('substituteteacher', 'ID'),
            'registry_id' => Yii::t('substituteteacher', 'Registry ID'),
            'year' => Yii::t('substituteteacher', 'Year'),
            'status' => Yii::t('substituteteacher', 'Status'),
            'points' => Yii::t('substituteteacher', 'Points'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlacementPreferences()
    {
        return $this->hasMany(PlacementPreference::className(), ['teacher_id' => 'id'])
            ->orderBy(['[[order]]' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlacementPreferencePrefectures()
    {
        return $this->hasMany(Prefecture::className(), ['id' => 'prefecture_id'])
            ->viaTable('{{%stplacement_preference}}', ['prefecture_id' => 'id'])
            ->from(['prefectures' => '{{%stprefecture}}']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistry()
    {
        return $this->hasOne(TeacherRegistry::className(), ['id' => 'registry_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherStatusAudits()
    {
        return $this->hasMany(TeacherStatusAudit::className(), ['teacher_id' => 'id']);
    }

    /**
     * Get a list of available choices in the form of
     * ID => LABEL suitable for select lists.
     *
     */
    public static function getChoices($for = 'status')
    {
        $choices = [];
        if ($for === 'status') {
            return [
                self::TEACHER_STATUS_ELIGIBLE => Yii::t('substituteteacher', 'Eligible for appointment'),
                self::TEACHER_STATUS_APPOINTED => Yii::t('substituteteacher', 'Teacher appointed'),
                self::TEACHER_STATUS_NEGATION => Yii::t('substituteteacher', 'Teacher denied appointment'),
            ];
        }

        return $choices;
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->name = ($this->registry ? $this->registry->name : '-') . " ({$this->year})";

        switch ($this->status) {
            case self::TEACHER_STATUS_ELIGIBLE:
                $this->status_label = Yii::t('substituteteacher', 'Eligible for appointment');
                break;
            case self::TEACHER_STATUS_APPOINTED:
                $this->status_label = Yii::t('substituteteacher', 'Teacher appointed');
                break;
            case self::TEACHER_STATUS_NEGATION:
                $this->status_label = Yii::t('substituteteacher', 'Teacher denied appointment');
                break;
            default:
                $this->status_label = null;
                break;
        }
    }

    /**
     * @inheritdoc
     * @return TeacherQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TeacherQuery(get_called_class());
    }
}
