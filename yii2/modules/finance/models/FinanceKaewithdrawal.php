<?php

namespace app\modules\finance\models;

use app\modules\finance\Module;
use Yii;

/**
 * This is the model class for table "{{%finance_kaewithdrawal}}".
 *
 * @property integer $kaewithdr_id
 * @property string $kaewithdr_amount
 * @property string $kaewithdr_decision
 * @property string $kaewithdr_date
 * @property integer $kaecredit_id
 *
 * @property FinanceExpenditure[] $financeExpenditures
 * @property FinanceKaecredit $kaecredit
 */
class FinanceKaewithdrawal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_kaewithdrawal}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kaewithdr_amount', 'kaewithdr_decision', 'kaewithdr_date', 'kaecredit_id'], 'required'],
            [['kaewithdr_amount', 'kaecredit_id'], 'integer'],
            [['kaewithdr_date'], 'safe'],
            [['kaewithdr_decision'], 'string', 'max' => 255],
            [['kaewithdr_decisionfile'], 'string', 'max' => 200],
            [['kaecredit_id'], 'exist', 'skipOnError' => true, 'targetClass' => FinanceKaecredit::className(), 'targetAttribute' => ['kaecredit_id' => 'kaecredit_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kaewithdr_id' => Module::t('modules/finance/app', 'Withdrawal'),
            'kaewithdr_amount' => Module::t('modules/finance/app', 'Withdrawal Amount'),
            'kaewithdr_decision' => Module::t('modules/finance/app', 'Withdrawal Decision'),
            'kaewithdr_decisionfile' => Module::t('modules/finance/app', 'Decision File'),
            'kaewithdr_date' => Module::t('modules/finance/app', 'Created Date'),
            'kaecredit_id' => Module::t('modules/finance/app', 'Credit ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinanceExpenditures()
    {
        //return $this->hasMany(FinanceExpenditure::className(), ['kaewithdr_id' => 'kaewithdr_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKaecredit()
    {
        return $this->hasOne(FinanceKaecredit::className(), ['kaecredit_id' => 'kaecredit_id']);
    }

    /**
     * @inheritdoc
     * @return FinanceKaewithdrawalQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FinanceKaewithdrawalQuery(get_called_class());
    }
        
    /**
     * Returns the balance of the all the credits of the RCNs in $kaeslist ($kaesListModel = FinanceKae::find()->all()) of the year $year 
     * @param integer $kaelist
     * @param integer $year
     * @return FinanceKaewithdrawalQuery the active query used by this AR class.
     */
    public static function getAllWithdrawalsBalance($kaeslist, $year){
        $kaewithdrsbalance = array();
        for($i = 0; $i < count($kaeslist); $i++)
            $kaewithdrsbalance[$kaeslist[$i]['kae_id']] = FinanceKaewithdrawal::getWithdrawalsBalance(
                FinanceKaecredit::getKaecreditId($kaeslist[$i]['kae_id'], $year));
        return $kaewithdrsbalance;
    }
    
    /**
     * Returns the balance of the RCN credit with id $kaecredit_id 
     * @param integer $kaecredit_id
     * @return FinanceKaewithdrawalQuery the active query used by this AR class.
     */
    public static function getWithdrawalsBalance($kaecredit_id){
        $kaewithdrs = FinanceKaewithdrawal::find()->where(['kaecredit_id' => $kaecredit_id]);
        if(!count($kaewithdrs->all()))
            return 0;
        $kaewithdrs_sum = $kaewithdrs->sum('kaewithdr_amount'); 
        $kaewithdr_id = $kaewithdrs->one()->kaewithdr_id;

        $exps_sum = FinanceExpendwithdrawal::find()->where(['kaewithdr_id' => $kaewithdr_id])->sum('expwithdr_amount');
        //echo "<pre>"; echo ($kaewithdrs_sum - $exps_sum); "</pre>"; die();

        return ($kaewithdrs_sum - $exps_sum);
    }
    
    /**
     * Returns the total sum of withdraws carried out for the RCN credit with id $kaecredit_id (corresponds to an 
     * RCN for a specific year)    
     * @param integer $kaecredit_id
     * @return integer
     */
    public static function getWithdrawsSum($kaecredit_id){
        $sum = 0;
        $kaeWithdrwals = FinanceKaewithdrawal::find()->where(['kaecredit_id' => $kaecredit_id])->all();
        foreach ($kaeWithdrwals as $kaeWithdrwal)
            $sum += $kaeWithdrwal->kaewithdr_amount;
        return $sum;
    }
}
