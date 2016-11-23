<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "admapp_transport_print".
 *
 * @property integer $id
 * @property integer $transport
 * @property string $filename
 * @property string $create_ts
 * @property string $send_ts
 * @property string $to_emails
 *
 * @property Transport $transport0
 */
class TransportPrint extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admapp_transport_print';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transport'], 'integer'],
            [['filename'], 'required'],
            [['create_ts', 'send_ts'], 'safe'],
            [['filename'], 'string', 'max' => 255],
            [['to_emails'], 'string', 'max' => 1000],
            [['transport'], 'exist', 'skipOnError' => true, 'targetClass' => Transport::className(), 'targetAttribute' => ['transport' => 'id']],
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
            'filename' => Yii::t('app', 'Filename'),
            'create_ts' => Yii::t('app', 'Create Ts'),
            'send_ts' => Yii::t('app', 'Send Ts'),
            'to_emails' => Yii::t('app', 'To Emails'),
        ];
    }

    /**
     * 
     * @see TransportPrint::path
     * @return String 
     */
    public function getPath()
    {
        return $this->path($this->filename);
    }

    /**
     * 
     * @param String $filename 
     * @return String The full path to the file with filename
     */
    public static function path($filename)
    {
        $fname = basename($filename);
        return Yii::getAlias("@vendor/admapp/exports/transports/{$fname}");
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransport0()
    {
        return $this->hasOne(Transport::className(), ['id' => 'transport']);
    }   
}
