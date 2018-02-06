<?php

namespace app\modules\finance\models;

use Yii;

/**
 * This is the model class for table "{{%finance_expenditurestate}}".
 *
 * @property integer $exp_id
 * @property integer $state_id
 * @property string $expstate_date
 * @property string $expstate_comment
 *
 * @property FinanceExpenditure $exp
 * @property FinanceState $state
 */
class FinanceExpenditurestate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_expenditurestate}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['exp_id', 'state_id', 'expstate_date'], 'required'],
            [['exp_id', 'state_id'], 'integer'],
            [['expstate_date'], 'safe'],
            [['expstate_comment'], 'string', 'max' => 200],
            [['exp_id'], 'exist', 'skipOnError' => true, 'targetClass' => FinanceExpenditure::className(), 'targetAttribute' => ['exp_id' => 'exp_id']],
            [['state_id'], 'exist', 'skipOnError' => true, 'targetClass' => FinanceState::className(), 'targetAttribute' => ['state_id' => 'state_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'exp_id' => Yii::t('app', 'Exp ID'),
            'state_id' => Yii::t('app', 'State ID'),
            'expstate_date' => Yii::t('app', 'Expstate Date'),
            'expstate_comment' => Yii::t('app', 'Expstate Comment'),
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
    public function getState()
    {
        return $this->hasOne(FinanceState::className(), ['state_id' => 'state_id']);
    }

    /**
     * @inheritdoc
     * @return FinanceExpenditurestateQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FinanceExpenditurestateQuery(get_called_class());
    }
}
