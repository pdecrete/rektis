<?php

namespace app\modules\finance\models;

use Yii;

/**
 * This is the model class for table "{{%finance_expenditure}}".
 *
 * @property integer $exp_id
 * @property string $exp_amount
 * @property integer $exp_date
 * @property string $exp_lock
 * @property integer $exp_deleted
 * @property integer $kaewithdr_id
 * @property integer $suppl_id
 * @property integer $fpa_value
 *
 * @property FinanceExpenddeduction[] $financeExpenddeductions
 * @property FinanceDeduction[] $deducts
 * @property FinanceSupplier $suppl
 * @property FinanceKaewithdrawal $kaewithdr
 * @property FinanceExpenditurestate[] $financeExpenditurestates
 * @property FinanceState[] $states
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
            [['exp_amount', 'exp_date', 'exp_lock', 'kaewithdr_id', 'suppl_id', 'fpa_value'], 'required'],
            [['exp_amount', 'exp_date', 'exp_deleted', 'kaewithdr_id', 'suppl_id', 'fpa_value'], 'integer'],
            [['exp_lock'], 'string', 'max' => 255],
            [['suppl_id'], 'exist', 'skipOnError' => true, 'targetClass' => FinanceSupplier::className(), 'targetAttribute' => ['suppl_id' => 'suppl_id']],
            [['kaewithdr_id'], 'exist', 'skipOnError' => true, 'targetClass' => FinanceKaewithdrawal::className(), 'targetAttribute' => ['kaewithdr_id' => 'kaewithdr_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'exp_id' => Yii::t('app', 'Exp ID'),
            'exp_amount' => Yii::t('app', 'Exp Amount'),
            'exp_date' => Yii::t('app', 'Exp Date'),
            'exp_lock' => Yii::t('app', 'Exp Lock'),
            'exp_deleted' => Yii::t('app', 'Exp Deleted'),
            'kaewithdr_id' => Yii::t('app', 'Kaewithdr ID'),
            'suppl_id' => Yii::t('app', 'Suppl ID'),
            'fpa_value' => Yii::t('app', 'Fpa Value'),
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
    public function getKaewithdr()
    {
        return $this->hasOne(FinanceKaewithdrawal::className(), ['kaewithdr_id' => 'kaewithdr_id']);
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
