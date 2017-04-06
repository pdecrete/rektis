<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "admapp_transport_print".
 *
 * @property integer $id
 * @property integer $transport
 * @property integer $transport_print
 *
 * @property Transport $transport0
 * @property TransportPrint $transportprint0
 */
class TransportPrintConnection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admapp_transport_print_connection';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transport'], 'exist', 'skipOnError' => true, 'targetClass' => Transport::className(), 'targetAttribute' => ['transport' => 'id']],
            [['transport_print'], 'exist', 'skipOnError' => true, 'targetClass' => TransportPrint::className(), 'targetAttribute' => ['transport_print' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'transport' => Yii::t('app', 'Transport'),
            'transport_print' => Yii::t('app', 'Transport file'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransport0()
    {
        return $this->hasOne(Transport::className(), ['id' => 'transport']);
    }   

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransportPrint0()
    {
        return $this->hasOne(TransportPrint::className(), ['id' => 'transport_print']);
    }   

	public static function samePrintId($printid)
    {
        return TransportPrintConnection::find()
                        ->where([
                            'transport_print' => $printid
                        ])
                        ->all();
    }

	public static function sameTransportId($transportid)
    {
        return TransportPrintConnection::find()
                        ->where([
                            'transport' => $transportid
                        ])
                        ->all();
    }

}
