<?php

namespace app\modules\finance\models;

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
            [['kaecredit_id'], 'exist', 'skipOnError' => true, 'targetClass' => FinanceKaecredit::className(), 'targetAttribute' => ['kaecredit_id' => 'kaecredit_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kaewithdr_id' => Yii::t('app', 'Kaewithdr ID'),
            'kaewithdr_amount' => Yii::t('app', 'Kaewithdr Amount'),
            'kaewithdr_decision' => Yii::t('app', 'Kaewithdr Decision'),
            'kaewithdr_date' => Yii::t('app', 'Kaewithdr Date'),
            'kaecredit_id' => Yii::t('app', 'Kaecredit ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinanceExpenditures()
    {
        return $this->hasMany(FinanceExpenditure::className(), ['kaewithdr_id' => 'kaewithdr_id']);
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
}
