<?php

namespace app\modules\finance\models;

use app\modules\finance\Module;

/**
 * This is the model class for table "{{%finance_kaecredit}}".
 *
 * @property integer $kaecredit_id
 * @property string $kaecredit_amount
 * @property string $kaecredit_date
 * @property string $kaecredit_updated
 * @property integer $year
 * @property integer $kae_id
 *
 * @property FinanceYear $year0
 * @property FinanceKae $kae
 * @property FinanceKaewithdrawal[] $financeKaewithdrawals
 */
class FinanceKaecredit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_kaecredit}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kaecredit_amount', 'kaecredit_date', 'year', 'kae_id'], 'required'],
            [['year'], 'integer'],
            [['kaecredit_amount', 'kae_id'], 'number'],
            [['kaecredit_date', 'kaecredit_updated'], 'safe'],
            [['year'], 'exist', 'skipOnError' => true, 'targetClass' => FinanceYear::className(), 'targetAttribute' => ['year' => 'year']],
            [['kae_id'], 'exist', 'skipOnError' => true, 'targetClass' => FinanceKae::className(), 'targetAttribute' => ['kae_id' => 'kae_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kaecredit_id' => Module::t('modules/finance/app', 'RCN Credit ID'),
            'kaecredit_amount' => Module::t('modules/finance/app', 'RCN Credit Amount'),
            'kaecredit_date' => Module::t('modules/finance/app', 'RCN Credit Created Date'),
            'kaecredit_updated' => Module::t('modules/finance/app', 'RCN Credit Updated Date'),
            'year' => Module::t('modules/finance/app', 'Year'),
            'kae_id' => Module::t('modules/finance/app', 'RCN'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getYear0()
    {
        return $this->hasOne(FinanceYear::className(), ['year' => 'year']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKae()
    {
        return $this->hasOne(FinanceKae::className(), ['kae_id' => 'kae_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    /*    public function getFinanceKaewithdrawals()
        {
            return $this->hasMany(FinanceKaewithdrawal::className(), ['kaecredit_id' => 'kaecredit_id']);
        }
    */
    /**
     * @inheritdoc
     * @return FinanceKaecreditQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FinanceKaecreditQuery(get_called_class());
    }

    public static function getKaecreditId($kae_id, $year)
    {
        return FinanceKaecredit::find()->where(['kae_id' => $kae_id, 'year' => $year])->one()->kaecredit_id;
    }

    public static function getSumKaeCredits($year)
    {
        return FinanceKaecredit::find()->where(['year' => $year])->sum('kaecredit_amount');
    }
}
