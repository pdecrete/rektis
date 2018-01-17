<?php

namespace app\modules\SubstituteTeacher\models;

use Yii;

/**
 * This is the model class for table "{{%stteacher}}".
 *
 * @property integer $id
 * @property integer $registry_id
 * @property integer $year
 * @property integer $status
 * @property string $points
 *
 * @property PlacementPreference[] $placementPreferences
 * @property TeacherRegistry $registry
 * @property TeacherStatusAudit[] $teacherStatusAudits
 */
class Teacher extends \yii\db\ActiveRecord
{
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
            [['year'], 'required'],
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
        return $this->hasMany(PlacementPreference::className(), ['teacher_id' => 'id']);
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
     * @inheritdoc
     * @return TeacherQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TeacherQuery(get_called_class());
    }
}
