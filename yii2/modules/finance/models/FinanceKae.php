<?php

namespace app\modules\finance\models;

use app\modules\finance\Module;

/**
 * This is the model class for table "{{%finance_kae}}".
 *
 * @property integer $kae_id
 * @property string $kae_title
 * @property string $kae_description
 *
 * @property FinanceKaecredit[] $financeKaecredits
 * @property FinanceYear[] $years
 */
class FinanceKae extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_kae}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kae_id', 'kae_title'], 'required'],
            [['kae_id'], 'number'],
            [['kae_title'], 'string', 'max' => 255],
            [['kae_description'], 'string', 'max' => 1024],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kae_id' => Module::t('modules/finance/app', 'RCN'),
            'kae_title' => Module::t('modules/finance/app', 'RCN Title'),
            'kae_description' => Module::t('modules/finance/app', 'RCN Description'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinanceKaecredits()
    {
        return $this->hasMany(FinanceKaecredit::className(), ['kae_id' => 'kae_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getYears()
    {
        return $this->hasMany(FinanceYear::className(), ['year' => 'year'])->viaTable('{{%finance_kaecredit}}', ['kae_id' => 'kae_id']);
    }

    /**
     * @inheritdoc
     * @return FinanceKaeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FinanceKaeQuery(get_called_class());
    }
}
