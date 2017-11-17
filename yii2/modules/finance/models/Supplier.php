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
 * @property integer $suppl_taxoffice
 *
 * @property FinanceExpenditure[] $financeExpenditures
 * @property FinanceInvoice[] $financeInvoices
 */
class Supplier extends \yii\db\ActiveRecord
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
            [['suppl_name', 'suppl_vat', 'suppl_iban', 'suppl_employerid', 'suppl_taxoffice'], 'required'],
            [['suppl_vat', 'suppl_phone', 'suppl_fax', 'suppl_taxoffice'], 'integer'],
            [['suppl_name', 'suppl_address'], 'string', 'max' => 255],
            [['suppl_iban'], 'string', 'max' => 27],
            [['suppl_employerid'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'suppl_id' => Yii::t('app', 'Suppl ID'),
            'suppl_name' => Yii::t('app', 'Suppl Name'),
            'suppl_vat' => Yii::t('app', 'Suppl Vat'),
            'suppl_address' => Yii::t('app', 'Suppl Address'),
            'suppl_phone' => Yii::t('app', 'Suppl Phone'),
            'suppl_fax' => Yii::t('app', 'Suppl Fax'),
            'suppl_iban' => Yii::t('app', 'Suppl Iban'),
            'suppl_employerid' => Yii::t('app', 'Suppl Employerid'),
            'suppl_taxoffice' => Yii::t('app', 'Suppl Taxoffice'),
        ];
    }

    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinanceExpenditures()
    {
        // return $this->hasMany(FinanceExpenditure::className(), ['suppl_id' => 'suppl_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinanceInvoices()
    {
        // return $this->hasMany(FinanceInvoice::className(), ['suppl_id' => 'suppl_id']);
    }

    /**
     * @inheritdoc
     * @return SupplierQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SupplierQuery(get_called_class());
    }
}
