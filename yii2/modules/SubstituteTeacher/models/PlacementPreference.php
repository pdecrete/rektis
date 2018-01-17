<?php

namespace app\modules\SubstituteTeacher\models;

use Yii;

/**
 * This is the model class for table "{{%stplacement_preference}}".
 *
 * @property integer $id
 * @property integer $teacher_id
 * @property integer $prefecture_id
 * @property integer $school_type
 * @property integer $order
 *
 * @property Prefecture $prefecture
 * @property Teacher $teacher
 */
class PlacementPreference extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stplacement_preference}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['teacher_id', 'prefecture_id', 'school_type', 'order'], 'integer'],
            [['order'], 'required'],
            [['teacher_id', 'prefecture_id', 'school_type', 'order'], 'unique', 'targetAttribute' => ['teacher_id', 'prefecture_id', 'school_type', 'order'], 'message' => 'The combination of Teacher ID, Prefecture ID, School Type and Order has already been taken.'],
            [['prefecture_id'], 'exist', 'skipOnError' => true, 'targetClass' => Prefecture::className(), 'targetAttribute' => ['prefecture_id' => 'id']],
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
            'prefecture_id' => Yii::t('substituteteacher', 'Prefecture ID'),
            'school_type' => Yii::t('substituteteacher', 'School Type'),
            'order' => Yii::t('substituteteacher', 'Order'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrefecture()
    {
        return $this->hasOne(Prefecture::className(), ['id' => 'prefecture_id']);
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
     * @return PlacementPreferenceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PlacementPreferenceQuery(get_called_class());
    }
}
