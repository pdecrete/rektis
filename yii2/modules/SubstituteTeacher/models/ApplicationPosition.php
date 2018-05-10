<?php

namespace app\modules\SubstituteTeacher\models;

use Yii;

/**
 * This is the model class for table "{{%Application_position}}".
 *
 * @property integer $id
 * @property integer $application_id
 * @property integer $call_position_id
 * @property integer $order
 * @property integer $updated
 * @property integer $deleted
 *
 * @property Application $application
 * @property CallPosition $callPosition
 */
class ApplicationPosition extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stapplication_position}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['application_id', 'call_position_id', 'order', 'updated', 'deleted'], 'integer'],
            [['order', 'updated', 'deleted'], 'required'],
            [['application_id'], 'exist', 'skipOnError' => true, 'targetClass' => Application::className(), 'targetAttribute' => ['application_id' => 'id']],
            [['call_position_id'], 'exist', 'skipOnError' => true, 'targetClass' => CallPosition::className(), 'targetAttribute' => ['call_position_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('substituteteacher', 'ID'),
            'application_id' => Yii::t('substituteteacher', 'Application ID'),
            'call_position_id' => Yii::t('substituteteacher', 'Call Position ID'),
            'order' => Yii::t('substituteteacher', 'Order'),
            'updated' => Yii::t('substituteteacher', 'Updated'),
            'deleted' => Yii::t('substituteteacher', 'Deleted'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplication()
    {
        return $this->hasOne(Application::className(), ['id' => 'application_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCallPosition()
    {
        return $this->hasOne(CallPosition::className(), ['id' => 'call_position_id']);
    }

    /**
     * @inheritdoc
     * @return ApplicationPositionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ApplicationPositionQuery(get_called_class());
    }
}
