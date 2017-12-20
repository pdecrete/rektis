<?php

namespace app\modules\finance\models;

use Yii;

/**
 * This is the model class for table "{{%finance_fpa}}".
 *
 * @property integer $fpa_value
 */
class FinanceFpa extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_fpa}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fpa_value'], 'required'],
            [['fpa_value'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fpa_value' => Yii::t('app', 'Fpa Value'),
        ];
    }

    /**
     * @inheritdoc
     * @return FinanceFpaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FinanceFpaQuery(get_called_class());
    }
}
