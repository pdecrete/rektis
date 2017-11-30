<?php

namespace app\modules\finance\models;

use Yii;

/**
 * This is the model class for table "{{%finance_year}}".
 *
 * @property integer $year
 * @property string $year_credit
 * @property integer $year_iscurrent
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
            [['year', 'year_iscurrent', 'year_lock'], 'integer'],
            [['year_credit'], 'number']
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
            'year_iscurrent' => Yii::t('app', 'Year Iscurrent'),
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

    /**
     * Returns the amount credited for the year $year.
     * @param integer $year
     * @return string
     */
    public static function getYearCredit($year)
    {
        return FinanceYear::find()->select(['year_credit'])->where(['year' => $year])->one()->year_credit;
    }
    
    /**
     * Returns true if $year is the currently working year, otherwise returns false.
     * @param integer $year
     * @return boolean
     */
    public static function isCurrent($year)
    {
        $model = FinanceYear::find()->where(['year' => $year])->one();
        return ($model->year_iscurrent == 1);
    }

    /**
     * Returns true if $year is locked, otherwise returns false.
     * @param integer $year
     * @return boolean
     */
    public static function isLocked($year)
    {
        $model = FinanceYear::find()->where(['year' => $year])->one();
        return ($model->year_lock == 1);
    }
}
