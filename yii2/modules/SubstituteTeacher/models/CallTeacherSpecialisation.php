<?php

namespace app\modules\SubstituteTeacher\models;

use Yii;

/**
 * This is the model class for table "{{%stcall_teacher_specialisation}}".
 *
 * @property integer $id
 * @property integer $call_id
 * @property integer $specialisation_id
 * @property integer $teachers
 * @property string $teachers_called
 *
 * @property Call $call
 * @property Specialisation $specialisation
 */
class CallTeacherSpecialisation extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stcall_teacher_specialisation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['teachers', 'required'],
            [['call_id', 'specialisation_id', 'teachers'], 'integer'],
            [['teachers_called'], 'string'],
            [['call_id', 'specialisation_id'], 'unique', 'targetAttribute' => ['call_id', 'specialisation_id'], 'message' => 'The combination of Call ID and Specialisation ID has already been taken.'],
            [['call_id'], 'exist', 'skipOnError' => true, 'targetClass' => Call::className(), 'targetAttribute' => ['call_id' => 'id']],
            [['specialisation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Specialisation::className(), 'targetAttribute' => ['specialisation_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('substituteteacher', 'ID'),
            'call_id' => Yii::t('substituteteacher', 'Call'),
            'specialisation_id' => Yii::t('substituteteacher', 'Specialisation'),
            'teachers' => Yii::t('substituteteacher', 'Number of teachers'),
            'teachers_called' => Yii::t('substituteteacher', 'Teachers Called'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCall()
    {
        return $this->hasOne(Call::className(), ['id' => 'call_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpecialisation()
    {
        return $this->hasOne(Specialisation::className(), ['id' => 'specialisation_id']);
    }

    /**
     * @inheritdoc
     * @return CallTeacherSpecialisationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CallTeacherSpecialisationQuery(get_called_class());
    }
}
