<?php

namespace app\modules\SubstituteTeacher\models;

use Yii;

/**
 * This is the model class for table "{{%stcall}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $application_start
 * @property string $application_end
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CallPosition[] $callPositions
 */
class Call extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stcall}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description'], 'required'],
            [['description'], 'string'],
            [['application_start', 'application_end', 'created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'application_start' => Yii::t('app', 'Application Start'),
            'application_end' => Yii::t('app', 'Application End'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCallPositions()
    {
        return $this->hasMany(CallPosition::className(), ['call_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return CallQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CallQuery(get_called_class());
    }
}
