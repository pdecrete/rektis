<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%transport_distance}}".
 *
 * @property integer $id
 * @property string $name
 * @property double $distance
 *
 * @property Transport[] $transports
 */
class TransportDistance extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%transport_distance}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'distance'], 'required'],
            [['distance'], 'number'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'distance' => Yii::t('app', 'Distance'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransports()
    {
        return $this->hasMany(Transport::className(), ['from_to' => 'id']);
    }
}
