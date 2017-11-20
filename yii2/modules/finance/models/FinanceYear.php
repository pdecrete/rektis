<?php

namespace app\modules\finance\models;

use Yii;

/**
 * This is the model class for table "{{%finance_year}}".
 *
 * @property integer $year
 * @property string $year_credit
 * @property integer $year_lock
 *
 * @property FinanceKaecredit[] $financeKaecredits
 */
class FinanceYear extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_year}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['year', 'year_credit'], 'required'],
            [['year', 'year_lock'], 'integer'],
            [['year_credit'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'year' => Yii::t('app', 'Year'),
            'year_credit' => Yii::t('app', 'Year Credit'),
            'year_lock' => Yii::t('app', 'Year Lock'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinanceKaecredits()
    {
        return $this->hasMany(FinanceKaecredit::className(), ['year' => 'year']);
    }

    /**
     * @inheritdoc
     * @return FinanceYearQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FinanceYearQuery(get_called_class());
    }
}
