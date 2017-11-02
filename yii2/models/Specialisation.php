<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%specialisation}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 *
 * @property Employee[] $employees
 */
class Specialisation extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%specialisation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'required'],
            [['code'], 'string', 'max' => 10],
            [['name'], 'string', 'max' => 100],
            [['code'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'SpecName'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(Employee::className(), ['specialisation' => 'id']);
    }

    /**
     * @inheritdoc
     * @return AdmappSpecialisationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SpecialisationQuery(get_called_class());
    }
}
