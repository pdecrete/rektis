<?php

namespace app\modules\finance\models;

use app\modules\finance\Module;

/**
 * This is the model class for table "{{%finance_expenditure}}".
 *
 * @property integer $exp_id
 * @property string $exp_amount
 * @property integer $exp_date
 * @property string $exp_lock
 * @property integer $exp_deleted
 * @property integer $suppl_id
 * @property integer $fpa_value
 *
 * @property FinanceExpenddeduction[] $financeExpenddeductions
 * @property FinanceDeduction[] $deducts
 * @property FinanceSupplier $suppl
 * @property FinanceExpenditurestate[] $financeExpenditurestates
 * @property FinanceState[] $states
 * @property FinanceExpendwithdrawal[] $financeExpendwithdrawals
 * @property FinanceKaewithdrawal[] $kaewithdrs
 * @property FinanceInvoice $financeInvoice
 */
class FinanceExpenditure extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_expenditure}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['exp_amount', 'exp_date', 'exp_description', 'exp_lock', 'suppl_id', 'fpa_value'], 'required'],
            [['exp_lock', 'exp_deleted', 'suppl_id'], 'integer'],
            [['exp_amount'], 'number'],
            [['exp_date'], 'safe'],
            [['suppl_id'], 'exist', 'skipOnError' => true, 'targetClass' => FinanceSupplier::className(), 'targetAttribute' => ['suppl_id' => 'suppl_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'exp_id' => Module::t('modules/finance/app', 'ID'),
            'exp_amount' => Module::t('modules/finance/app', 'Amount'),
            'exp_description' => Module::t('modules/finance/app', 'Description'),
            'exp_date' => Module::t('modules/finance/app', 'Date'),
            'exp_lock' => Module::t('modules/finance/app', 'Locked'),
            'exp_deleted' => Module::t('modules/finance/app', 'Deleted'),
            'suppl_id' => Module::t('modules/finance/app', 'Supplier'),
            'fpa_value' => Module::t('modules/finance/app', 'VAT')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinanceExpenddeductions()
    {
        return $this->hasMany(FinanceExpenddeduction::className(), ['exp_id' => 'exp_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeducts()
    {
        return $this->hasMany(FinanceDeduction::className(), ['deduct_id' => 'deduct_id'])->viaTable('{{%finance_expenddeduction}}', ['exp_id' => 'exp_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSuppl()
    {
        return $this->hasOne(FinanceSupplier::className(), ['suppl_id' => 'suppl_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinanceExpenditurestates()
    {
        return $this->hasMany(FinanceExpenditurestate::className(), ['exp_id' => 'exp_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStates()
    {
        return $this->hasMany(FinanceState::className(), ['state_id' => 'state_id'])->viaTable('{{%finance_expenditurestate}}', ['exp_id' => 'exp_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinanceExpendwithdrawals()
    {
        return $this->hasMany(FinanceExpendwithdrawal::className(), ['exp_id' => 'exp_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKaewithdrs()
    {
        return $this->hasMany(FinanceKaewithdrawal::className(), ['kaewithdr_id' => 'kaewithdr_id'])->viaTable('{{%finance_expendwithdrawal}}', ['exp_id' => 'exp_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinanceInvoice()
    {
        return $this->hasOne(FinanceInvoice::className(), ['exp_id' => 'exp_id']);
    }

    /**
     * @inheritdoc
     * @return FinanceExpenditureQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FinanceExpenditureQuery(get_called_class());
    }
}
