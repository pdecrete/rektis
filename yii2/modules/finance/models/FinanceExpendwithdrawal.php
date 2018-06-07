<?php

namespace app\modules\finance\models;

use app\modules\finance\Module;
use app\modules\finance\components\Money;

/**
 * This is the model class for table "{{%finance_expendwithdrawal}}".
 *
 * @property integer $kaewithdr_id
 * @property integer $exp_id
 * @property string $expwithdr_amount
 *
 * @property FinanceExpenditure $exp
 * @property FinanceKaewithdrawal $kaewithdr
 */
class FinanceExpendwithdrawal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_expendwithdrawal}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kaewithdr_id', 'exp_id', 'expwithdr_amount', 'expwithdr_order'], 'safe'],
            [['kaewithdr_id', 'exp_id', 'expwithdr_amount', 'expwithdr_order'], 'integer'],
            [['exp_id'], 'exist', 'skipOnError' => true, 'targetClass' => FinanceExpenditure::className(), 'targetAttribute' => ['exp_id' => 'exp_id']],
            [['kaewithdr_id'], 'exist', 'skipOnError' => true, 'targetClass' => FinanceKaewithdrawal::className(), 'targetAttribute' => ['kaewithdr_id' => 'kaewithdr_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kaewithdr_id' => Module::t('modules/finance/app', 'Withdrawal'),
            'exp_id' => Module::t('modules/finance/app', 'Expenditure'),
            'expwithdr_amount' => Module::t('modules/finance/app', 'Amount'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExp()
    {
        return $this->hasOne(FinanceExpenditure::className(), ['exp_id' => 'exp_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKaewithdr()
    {
        return $this->hasOne(FinanceKaewithdrawal::className(), ['kaewithdr_id' => 'kaewithdr_id']);
    }

    /**
     * @inheritdoc
     * @return FinanceExpendwithdrawalQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FinanceExpendwithdrawalQuery(get_called_class());
    }

    /**
     * Returns the sum of the expenditures carried out for the withdrawal with id $kaewithdr_id
     *
     * @param integer $kaewithr_id
     * @return integer
     */
    public static function getExpendituresSum($kaewithdr_id)
    {
        $expenditures_sum = 0;
        $expend_withdrawals = FinanceExpendwithdrawal::find()->where(['kaewithdr_id' => $kaewithdr_id])->all();
        foreach ($expend_withdrawals as $expend_withdrawal) {
            $expenditure_fpa = Money::toDecimalPercentage(FinanceExpenditure::findOne(['exp_id' => $expend_withdrawal['exp_id']])['fpa_value'], false);
            $expenditures_sum += $expend_withdrawal->expwithdr_amount + $expend_withdrawal->expwithdr_amount*$expenditure_fpa;
        }
        return round($expenditures_sum, 0);
    }

    /**
     * Returns the balance of the the withdrawal with id $kaewithdr_id
     *
     * @param integer $kaewithr_id
     * @return integer
     */
    public static function getWithdrawalBalance($kaewithdr_id)
    {
        $expenditures_sum = FinanceExpendwithdrawal::getExpendituresSum($kaewithdr_id);
        $withdrawal_amount = FinanceKaewithdrawal::find()->where(['kaewithdr_id' => $kaewithdr_id])->one()['kaewithdr_amount'];
        return $withdrawal_amount - $expenditures_sum;
    }
}
