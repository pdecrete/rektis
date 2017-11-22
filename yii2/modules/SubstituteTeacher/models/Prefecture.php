<?php

namespace app\modules\SubstituteTeacher\models;

use Yii;

/**
 * This is the model class for table "{{%stprefecture}}".
 *
 * @property integer $id
 * @property string $region
 * @property string $prefecture
 *
 * @property Position[] $positions
 */
class Prefecture extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stprefecture}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['prefecture'], 'required'],
            [['region', 'prefecture'], 'string', 'max' => 150],
            [['prefecture'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'region' => Yii::t('app', 'Region'),
            'prefecture' => Yii::t('app', 'Prefecture'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPositions()
    {
        return $this->hasMany(Position::className(), ['prefecture_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return PrefectureQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PrefectureQuery(get_called_class());
    }
}
