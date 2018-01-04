<?php

namespace app\modules\finance\models;

use Yii;

/**
 * This is the model class for table "{{%finance_deduction}}".
 *
 * @property integer $deduct_id
 * @property string $deduct_name
 * @property string $deduct_description
 * @property string $deduct_date
 * @property integer $deduct_percentage
 * @property string $deduct_downlimit
 * @property string $deduct_uplimit
 * @property integer $detuct_obsolete
 *
 * @property FinanceExpenddeduction[] $financeExpenddeductions
 * @property FinanceExpenditure[] $exps
 */
class FinanceDeduction extends \yii\db\ActiveRecord
{
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
            [['deduct_name', 'deduct_date', 'deduct_percentage'], 'required'],
            [['deduct_date'], 'safe'],
            [['deduct_percentage', 'deduct_downlimit', 'deduct_uplimit', 'detuct_obsolete'], 'integer'],
            [['deduct_name'], 'string', 'max' => 100],
            [['deduct_description'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'deduct_id' => Yii::t('app', 'Deduct ID'),
            'deduct_name' => Yii::t('app', 'Deduct Name'),
            'deduct_description' => Yii::t('app', 'Deduct Description'),
            'deduct_date' => Yii::t('app', 'Deduct Date'),
            'deduct_percentage' => Yii::t('app', 'Deduct Percentage'),
            'deduct_downlimit' => Yii::t('app', 'Deduct Downlimit'),
            'deduct_uplimit' => Yii::t('app', 'Deduct Uplimit'),
            'detuct_obsolete' => Yii::t('app', 'Detuct Obsolete'),
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

    /**
     * @inheritdoc
     * @return FinanceDeductionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FinanceDeductionQuery(get_called_class());
    }
}
