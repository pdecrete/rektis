<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%service}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $information
 *
 * @property Employee[] $employees
 * @property Employee[] $employees0
 */
class Service extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%service}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'information'], 'required'],
            [['name'], 'string', 'max' => 100],
            [['information'], 'string', 'max' => 500],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Yii::t('app', 'Name'),
            'information' => Yii::t('app', 'Information'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(Employee::className(), ['service_organic' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees0()
    {
        return $this->hasMany(Employee::className(), ['service_serve' => 'id']);
    }

    /**
     * @inheritdoc
     * @return ServiceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ServiceQuery(get_called_class());
    }
}
