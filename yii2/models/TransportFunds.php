<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%transport_funds}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $date
 * @property string $ada
 * @property integer $service
 * @property string $code
 * @property string $kae
 * @property boolean $count_flag
 * @property number $amount
 *
 * @property Service $service0
 */
class TransportFunds extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%transport_funds}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['service'], 'integer'],
            [['amount'], 'number'],
            [['count_flag'], 'boolean'],
            [['date'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['ada', 'code'], 'string', 'max' => 20],
            [['kae'], 'string', 'max' => 10],
            [['service'], 'exist', 'skipOnError' => true, 'targetClass' => Service::className(), 'targetAttribute' => ['service' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Protocol'),
            'date' => Yii::t('app', 'Date'),
            'ada' => Yii::t('app', 'ADA'),
            'service' => Yii::t('app', 'Service'),
            'code' => Yii::t('app', 'Budget'),
            'kae' => Yii::t('app', 'KAE'),
            'amount' => Yii::t('app', 'Amount'),
            'count_flag' => Yii::t('app', 'Count amount'),
        ];
    }

    public function kaeList()
    {
        return ['719'=>'719', '721'=>'721', '722'=>'722'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getService0()
    {
        return $this->hasOne(Service::className(), ['id' => 'service']);
    }
}
