<?php

namespace app\modules\schooltransport\models;

use Yii;

/**
 * This is the model class for table "{{%schoolunit}}".
 *
 * @property integer $school_id
 * @property string $school_name
 * @property integer $directorate_id
 *
 * @property Directorate $directorate
 * @property SchtransportTransport[] $schtransportTransports
 */
class Schoolunit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%schoolunit}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['school_name', 'directorate_id'], 'required'],
            [['directorate_id'], 'integer'],
            [['school_name'], 'string', 'max' => 200],
            [['directorate_id'], 'exist', 'skipOnError' => true, 'targetClass' => Directorate::className(), 'targetAttribute' => ['directorate_id' => 'directorate_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'school_id' => Yii::t('app', 'School ID'),
            'school_name' => Yii::t('app', 'Σχολείο'),
            'directorate_id' => Yii::t('app', 'Διεύθυνση Εκπαίδευσης Σχολείου'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDirectorate()
    {
        return $this->hasOne(Directorate::className(), ['directorate_id' => 'directorate_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchtransportTransports()
    {
        return $this->hasMany(SchtransportTransport::className(), ['school_id' => 'school_id']);
    }
}
