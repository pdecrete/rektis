<?php

namespace app\modules\finance\models;

use Yii;

/**
 * This is the model class for table "{{%finance_expenddeduction}}".
 *
 * @property integer $exp_id
 * @property integer $deduct_id
 *
 * @property FinanceExpenditure $exp
 * @property FinanceDeduction $deduct
 */
class FinanceExpenddeduction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_expenddeduction}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['exp_id', 'deduct_id'], 'required'],
            [['exp_id', 'deduct_id'], 'integer'],
            [['exp_id'], 'exist', 'skipOnError' => true, 'targetClass' => FinanceExpenditure::className(), 'targetAttribute' => ['exp_id' => 'exp_id']],
            [['deduct_id'], 'exist', 'skipOnError' => true, 'targetClass' => FinanceDeduction::className(), 'targetAttribute' => ['deduct_id' => 'deduct_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'exp_id' => Yii::t('app', 'Exp ID'),
            'deduct_id' => Yii::t('app', 'Deduct ID'),
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
    public function getDeduct()
    {
        return $this->hasOne(FinanceDeduction::className(), ['deduct_id' => 'deduct_id']);
    }

    /**
     * @inheritdoc
     * @return FinanceExpenddeductionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FinanceExpenddeductionQuery(get_called_class());
    }
}
