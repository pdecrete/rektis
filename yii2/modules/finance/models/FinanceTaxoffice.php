<?php

namespace app\modules\finance\models;

use app\modules\finance\Module;

/**
 * This is the model class for table "{{%finance_taxoffice}}".
 *
 * @property integer $taxoffice_id
 * @property string $taxoffice_name
 * @property string $taxoffice_prefecture
 *
 * @property FinanceSupplier[] $financeSuppliers
 */
class FinanceTaxoffice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_taxoffice}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['taxoffice_id', 'taxoffice_name'], 'required'],
            [['taxoffice_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'taxoffice_id' => Module::t('modules/finance/app', 'Code'),
            'taxoffice_name' => Module::t('modules/finance/app', 'Title'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinanceSuppliers()
    {
        return $this->hasMany(FinanceSupplier::className(), ['taxoffice_id' => 'taxoffice_id']);
    }

    /**
     * @inheritdoc
     * @return FinanceTaxofficeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FinanceTaxofficeQuery(get_called_class());
    }
}
