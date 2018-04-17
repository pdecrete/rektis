<?php

namespace app\modules\schooltransport\models;

use Yii;

/**
 * This is the model class for table "{{%schtransport_transportstate}}".
 *
 * @property integer $transport_id
 * @property integer $state_id
 * @property string $transportstate_date
 * @property string $transportstate_comment
 *
 * @property SchtransportTransport $transport
 * @property SchtransportState $state
 */
class SchtransportTransportstate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%schtransport_transportstate}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transport_id', 'state_id', 'transportstate_date'], 'required'],
            [['transport_id', 'state_id'], 'integer'],
            [['transportstate_date'], 'safe'],
            [['transportstate_comment'], 'string', 'max' => 200],
            [['transport_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchtransportTransport::className(), 'targetAttribute' => ['transport_id' => 'transport_id']],
            [['state_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchtransportState::className(), 'targetAttribute' => ['state_id' => 'state_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'transport_id' => Yii::t('app', 'Transport ID'),
            'state_id' => Yii::t('app', 'State ID'),
            'transportstate_date' => Yii::t('app', 'Transportstate Date'),
            'transportstate_comment' => Yii::t('app', 'Transportstate Comment'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransport()
    {
        return $this->hasOne(SchtransportTransport::className(), ['transport_id' => 'transport_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(SchtransportState::className(), ['state_id' => 'state_id']);
    }

    /**
     * @inheritdoc
     * @return SchtransportTransportstateQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SchtransportTransportstateQuery(get_called_class());
    }
}
