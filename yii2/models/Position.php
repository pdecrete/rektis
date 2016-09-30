<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%position}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $comments
 *
 * @property Employee[] $employees
 */
class Position extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%position}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'comments'], 'required'],
            [['comments'], 'string'],
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
            'comments' => Yii::t('app', 'Comments'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(Employee::className(), ['position' => 'id']);
    }

    /**
     * @inheritdoc
     * @return AdmappPositionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AdmappPositionQuery(get_called_class());
    }
}
