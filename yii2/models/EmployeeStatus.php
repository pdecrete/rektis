<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%employee_status}}".
 *
 * @property integer $id
 * @property string $name
 *
 * @property Employee[] $employees
 */
class EmployeeStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%employee_status}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 100],
            [['name'], 'unique']
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(Employee::className(), ['status' => 'id']);
    }

    /**
     * @inheritdoc
     * @return AdmappEmployeeStatusQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AdmappEmployeeStatusQuery(get_called_class());
    }
}
