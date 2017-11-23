<?php

namespace app\modules\SubstituteTeacher\models;

use Yii;

/**
 * This is the model class for table "{{%stcall_position}}".
 *
 * @property integer $id
 * @property integer $call_id
 * @property integer $position_id
 * @property integer $teachers_count
 * @property integer $hours_count
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Call $call
 * @property Position $position
 */
class CallPosition extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stcall_position}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['call_id', 'position_id', 'teachers_count', 'hours_count'], 'integer'],
            [['teachers_count', 'hours_count'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['call_id'], 'exist', 'skipOnError' => true, 'targetClass' => Call::className(), 'targetAttribute' => ['call_id' => 'id']],
            [['position_id'], 'exist', 'skipOnError' => true, 'targetClass' => Position::className(), 'targetAttribute' => ['position_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('substituteteacher', 'ID'),
            'call_id' => Yii::t('substituteteacher', 'Call ID'),
            'position_id' => Yii::t('substituteteacher', 'Position ID'),
            'teachers_count' => Yii::t('substituteteacher', 'Teachers Count'),
            'hours_count' => Yii::t('substituteteacher', 'Hours Count'),
            'created_at' => Yii::t('substituteteacher', 'Created At'),
            'updated_at' => Yii::t('substituteteacher', 'Updated At'),
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
    public function getPosition()
    {
        return $this->hasOne(Position::className(), ['id' => 'position_id']);
    }

    /**
     * @inheritdoc
     * @return CallPositionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CallPositionQuery(get_called_class());
    }
}
