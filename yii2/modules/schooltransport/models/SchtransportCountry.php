<?php

namespace app\modules\schooltransport\models;

use Yii;

/**
 * This is the model class for table "{{%schtransport_country}}".
 *
 * @property integer $country_id
 * @property string $country_name
 */
class SchtransportCountry extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%schtransport_country}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_name'], 'required'],
            [['country_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'country_id' => Yii::t('app', 'Country ID'),
            'country_name' => Yii::t('app', 'Country Name'),
        ];
    }

    /**
     * @inheritdoc
     * @return SchtransportCountryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SchtransportCountryQuery(get_called_class());
    }
}
