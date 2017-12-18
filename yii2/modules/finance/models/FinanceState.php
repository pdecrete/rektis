<?php

namespace app\modules\finance\models;

use Yii;

/**
 * This is the model class for table "{{%finance_state}}".
 *
 * @property integer $state_id
 * @property string $state_name
 *
 * @property FinanceExpenditurestate[] $financeExpenditurestates
 * @property FinanceExpenditure[] $exps
 */
class FinanceState extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_state}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['state_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'state_id' => Yii::t('app', 'State ID'),
            'state_name' => Yii::t('app', 'State Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinanceExpenditurestates()
    {
        return $this->hasMany(FinanceExpenditurestate::className(), ['state_id' => 'state_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExps()
    {
        return $this->hasMany(FinanceExpenditure::className(), ['exp_id' => 'exp_id'])->viaTable('{{%finance_expenditurestate}}', ['state_id' => 'state_id']);
    }

    /**
     * @inheritdoc
     * @return FinanceStateQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FinanceStateQuery(get_called_class());
    }
}
