<?php

namespace app\modules\SubstituteTeacher\models;

use Yii;

/**
 * This is the model class for table "{{%stteacher_status_audit}}".
 *
 * @property integer $id
 * @property integer $teacher_id
 * @property integer $status
 * @property string $status_ts
 *
 * @property Teacher $teacher
 */
class TeacherStatusAudit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stteacher_status_audit}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['teacher_id', 'status'], 'integer'],
            [['status_ts'], 'safe'],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => Teacher::className(), 'targetAttribute' => ['teacher_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('substituteteacher', 'ID'),
            'teacher_id' => Yii::t('substituteteacher', 'Teacher ID'),
            'status' => Yii::t('substituteteacher', 'Status'),
            'status_ts' => Yii::t('substituteteacher', 'Status Ts'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacher()
    {
        return $this->hasOne(Teacher::className(), ['id' => 'teacher_id']);
    }

    /**
     * @inheritdoc
     * @return TeacherStatusAuditQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TeacherStatusAuditQuery(get_called_class());
    }
}
