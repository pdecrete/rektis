<?php

namespace app\modules\finance\models;

use app\modules\finance\Module;
use Yii;

/**
 * This is the model class for table "{{%finance_invoicetype}}".
 *
 * @property integer $invtype_id
 * @property string $invtype_title
 *
 * @property FinanceInvoice[] $financeInvoices
 */
class FinanceInvoicetype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_invoicetype}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invtype_title'], 'required'],
            [['invtype_title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invtype_id' => Module::t('modules/finance/app', 'Invtype ID'),
            'invtype_title' => Module::t('modules/finance/app', 'Title'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinanceInvoices()
    {
        return $this->hasMany(FinanceInvoice::className(), ['invtype_id' => 'invtype_id']);
    }

    /**
     * @inheritdoc
     * @return FinanceInvoicetypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FinanceInvoicetypeQuery(get_called_class());
    }
}
