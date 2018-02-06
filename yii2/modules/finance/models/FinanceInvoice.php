<?php

namespace app\modules\finance\models;

use app\modules\finance\Module;
use Yii;

/**
 * This is the model class for table "{{%finance_invoice}}".
 *
 * @property integer $inv_id
 * @property string $inv_number
 * @property integer $inv_date
 * @property string $inv_order
 * @property integer $inv_deleted
 * @property integer $suppl_id
 * @property integer $exp_id
 * @property integer $invtype_id
 *
 * @property FinanceSupplier $suppl
 * @property FinanceExpenditure $exp
 * @property FinanceInvoicetype $invtype
 */
class FinanceInvoice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_invoice}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['inv_number', 'inv_date', 'suppl_id', 'exp_id', 'invtype_id'], 'required'],
            [['inv_deleted', 'suppl_id', 'exp_id', 'invtype_id'], 'integer'],
            [['inv_number', 'inv_order'], 'string', 'max' => 255],
            [['inv_date'], 'safe'],
            [['exp_id'], 'unique'],
            [['suppl_id'], 'exist', 'skipOnError' => true, 'targetClass' => FinanceSupplier::className(), 'targetAttribute' => ['suppl_id' => 'suppl_id']],
            [['exp_id'], 'exist', 'skipOnError' => true, 'targetClass' => FinanceExpenditure::className(), 'targetAttribute' => ['exp_id' => 'exp_id']],
            [['invtype_id'], 'exist', 'skipOnError' => true, 'targetClass' => FinanceInvoicetype::className(), 'targetAttribute' => ['invtype_id' => 'invtype_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'inv_id' => Module::t('modules/finance/app', 'ID'),
            'inv_number' => Module::t('modules/finance/app', 'Number'),
            'inv_date' => Module::t('modules/finance/app', 'Date'),
            'inv_order' => Module::t('modules/finance/app', 'Order'),
            'inv_deleted' => Module::t('modules/finance/app', 'Deleted'),
            'suppl_id' => Module::t('modules/finance/app', 'Supplier'),
            'exp_id' => Module::t('modules/finance/app', 'Expenditure ID'),
            'invtype_id' => Module::t('modules/finance/app', 'Voucher Type'),
        ];
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
    public function getExp()
    {
        return $this->hasOne(FinanceExpenditure::className(), ['exp_id' => 'exp_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvtype()
    {
        return $this->hasOne(FinanceInvoicetype::className(), ['invtype_id' => 'invtype_id']);
    }

    /**
     * @inheritdoc
     * @return FinanceInvoiceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FinanceInvoiceQuery(get_called_class());
    }
}
