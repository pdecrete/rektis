<?php

namespace app\modules\finance\models;

use Yii;

/**
 * This is the model class for table "{{%finance_supplier}}".
 *
 * @property integer $suppl_id
 * @property string $suppl_name
 * @property integer $suppl_vat
 * @property string $suppl_address
 * @property integer $suppl_phone
 * @property integer $suppl_fax
 * @property string $suppl_iban
 * @property string $suppl_employerid
 * @property integer $taxoffice_id
 *
 * @property FinanceExpenditure[] $financeExpenditures
 * @property FinanceInvoice[] $financeInvoices
 * @property FinanceTaxoffice $taxoffice
 */
class FinanceSupplier extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_supplier}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['suppl_name', 'suppl_vat', 'suppl_iban', 'suppl_employerid', 'taxoffice_id'], 'required'],
            [['suppl_vat', 'suppl_phone', 'suppl_fax', 'taxoffice_id'], 'integer'],
            [['suppl_name', 'suppl_address'], 'string', 'max' => 255],
            [['suppl_iban'], 'string', 'max' => 27],
            [['suppl_employerid'], 'string', 'max' => 100],
            [['taxoffice_id'], 'exist', 'skipOnError' => true, 'targetClass' => FinanceTaxoffice::className(), 'targetAttribute' => ['taxoffice_id' => 'taxoffice_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'suppl_id' => Yii::t('app', 'Supplier ID'),
            'suppl_name' => Yii::t('app', 'Name'),
            'suppl_vat' => Yii::t('app', 'VAT'),
            'suppl_address' => Yii::t('app', 'Address'),
            'suppl_phone' => Yii::t('app', 'Phone'),
            'suppl_fax' => Yii::t('app', 'FAX'),
            'suppl_iban' => Yii::t('app', 'IBAN'),
            'suppl_employerid' => Yii::t('app', 'Employer id'),
            'taxoffice_id' => Yii::t('app', 'Tax Office'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinanceExpenditures()
    {
        return $this->hasMany(FinanceExpenditure::className(), ['suppl_id' => 'suppl_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinanceInvoices()
    {
        return $this->hasMany(FinanceInvoice::className(), ['suppl_id' => 'suppl_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaxoffice()
    {
        return $this->hasOne(FinanceTaxoffice::className(), ['taxoffice_id' => 'taxoffice_id']);
    }

    /**
     * @inheritdoc
     * @return FinanceSupplierQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FinanceSupplierQuery(get_called_class());
    }
}
