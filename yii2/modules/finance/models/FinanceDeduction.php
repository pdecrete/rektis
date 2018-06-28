<?php

namespace app\modules\finance\models;

use app\modules\finance\Module;

/**
 * This is the model class for table "{{%finance_deduction}}".
 *
 * @property integer $deduct_id
 * @property string $deduct_name
 * @property string $deduct_alias
 * @property string $deduct_description
 * @property string $deduct_date
 * @property integer $deduct_percentage
 * @property string $deduct_downlimit
 * @property string $deduct_uplimit
 * @property integer $deduct_obsolete
 *
 * @property FinanceExpenddeduction[] $financeExpenddeductions
 * @property FinanceExpenditure[] $exps
 */
class FinanceDeduction extends \yii\db\ActiveRecord
{
    const ALIAS_SERVICES_GOODS_UNDER_150EURO = 'services_goods_under_150euro';
    const ALIAS_SERVICES_OVER_150EURO = 'services_over_150euro';
    const ALIAS_GOODS_OVER_150EURO = 'goods_over_150euro';
    const ALIAS_NO_TAX = 'no_tax';
    const ALIAS_CLEANING = 'cleaning';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_deduction}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['deduct_name', 'deduct_date', 'deduct_percentage', 'deduct_alias'], 'required'],
            [['deduct_date'], 'safe'],
            [['deduct_obsolete'], 'integer'],
            [['deduct_downlimit', 'deduct_uplimit'], 'number'],
            [['deduct_name', 'deduct_alias'], 'string', 'max' => 100],
            [['deduct_description'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'deduct_id' => Module::t('modules/finance/app', 'Deduct ID'),
            'deduct_name' => Module::t('modules/finance/app', 'Title'),
            'deduct_alias' => Module::t('modules/finance/app', 'Text Key'),
            'deduct_description' => Module::t('modules/finance/app', 'Description'),
            'deduct_date' => Module::t('modules/finance/app', 'Date'),
            'deduct_percentage' => Module::t('modules/finance/app', 'Percentage'),
            'deduct_downlimit' => Module::t('modules/finance/app', 'Minimum Amount'),
            'deduct_uplimit' => Module::t('modules/finance/app', 'Maximum Amount'),
            'deduct_obsolete' => Module::t('modules/finance/app', 'Obsolete'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinanceExpenddeductions()
    {
        return $this->hasMany(FinanceExpenddeduction::className(), ['deduct_id' => 'deduct_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExps()
    {
        return $this->hasMany(FinanceExpenditure::className(), ['exp_id' => 'exp_id'])->viaTable('{{%finance_expenddeduction}}', ['deduct_id' => 'deduct_id']);
    }
    
    /*
     * Returns the constants representing the standard deductions that every expenditure should have have exactly one of them.
     */
    public static function getStandardFinanceDeductionsAlias()
    {
        return [self::ALIAS_GOODS_OVER_150EURO, self::ALIAS_SERVICES_OVER_150EURO, self::ALIAS_SERVICES_GOODS_UNDER_150EURO, self::ALIAS_NO_TAX];
    }
    
    /*
     * Returns an array with the standard deductions an expenditure may have.
     * 
     */
    public static function getStandardFinanceDeductions()
    {
        return FinanceDeduction::find()->where(['in', 'deduct_alias', self::getStandardFinanceDeductionsAlias()])->all();
    }
    
    /*
     * Returns an array with the ids of the standard deductions as saved in the database based on their unique alias.
     *
     */
    public static function getStandardFinanceDeductionsIds()
    {
        $standard_deductions = self::getStandardFinanceDeductions();
        $standard_deductions_ids = array();
        foreach ($standard_deductions as $standard_deduction)
            array_push($standard_deductions_ids, $standard_deduction['deduct_id']);
        
        return $standard_deductions_ids;
    }

    /**
     * @inheritdoc
     * @return FinanceDeductionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FinanceDeductionQuery(get_called_class());
    }
}
