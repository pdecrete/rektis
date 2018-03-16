<?php

namespace app\modules\schooltransport\models;

use Yii;

/**
 * This is the model class for table "{{%directorate}}".
 *
 * @property integer $directorate_id
 * @property string $directorate_name
 *
 * @property Schoolunit[] $schoolunits
 */
class Directorate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%directorate}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['directorate_name'], 'required'],
            [['directorate_name'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'directorate_id' => Yii::t('app', 'Directorate ID'),
            'directorate_name' => Yii::t('app', 'Directorate Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolunits()
    {
        return $this->hasMany(Schoolunit::className(), ['directorate_id' => 'directorate_id']);
    }

    /**
     * @inheritdoc
     * @return DirectorateQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DirectorateQuery(get_called_class());
    }
}
