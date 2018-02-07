<?php

namespace app\modules\finance\models;

use app\modules\finance\Module;
use app\modules\finance\components\Money;

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
            'fpa_value' => Module::t('modules/finance/app', 'Percentage'),
        ];
    }


    /**
     * Returns an array indexed with the VAT value level and the correspondent formatted value
     * i.e. 1300 --> 13.00%
     *      2400 --> 24.00%
     *
     * @return number[]|string[]
     */
    public static function getFpaLevels()
    {
        $levels = FinanceFpa::find()->all();
        //echo "<pre>"; print_r($levels); echo "</pre>"; die();
        $mapped_levels = [];
        foreach ($levels as $level) {
            $mapped_levels[$level->fpa_value] = Money::toPercentage($level->fpa_value, true);
        }
        return $mapped_levels;
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
