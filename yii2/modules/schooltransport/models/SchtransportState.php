<?php

namespace app\modules\schooltransport\models;

use Yii;

/**
 * This is the model class for table "{{%schtransport_state}}".
 *
 * @property integer $state_id
 * @property string $state_name
 *
 * @property SchtransportTransportstate[] $schtransportTransportstates
 * @property SchtransportTransport[] $transports
 */
class SchtransportState extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%schtransport_state}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['state_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'state_id' => Yii::t('app', 'State ID'),
            'state_name' => Yii::t('app', 'State Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchtransportTransportstates()
    {
        return $this->hasMany(SchtransportTransportstate::className(), ['state_id' => 'state_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransports()
    {
        return $this->hasMany(SchtransportTransport::className(), ['transport_id' => 'transport_id'])->viaTable('{{%schtransport_transportstate}}', ['state_id' => 'state_id']);
    }

    /**
     * @inheritdoc
     * @return SchtransportStateQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SchtransportStateQuery(get_called_class());
    }
}
