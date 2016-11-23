<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%transport_mode}}".
 *
 * @property integer $id
 * @property string $name
 * @property double $value
 * @property integer $out_limit
 * @property integer $deleted
 *
 * @property Transport[] $transports
 */
class TransportMode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%transport_mode}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['value'], 'number'],
            [['deleted' , 'out_limit'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'value' => Yii::t('app', 'Value'),
            'out_limit' => Yii::t('app', 'Out limit'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransports()
    {
        return $this->hasMany(Transport::className(), ['mode' => 'id']);
    }
}
