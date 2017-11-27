<?php

namespace app\modules\finance\models;

use Yii;

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
            [['year', 'kae_id'], 'integer'],
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
            'kaecredit_id' => Yii::t('app', 'Kaecredit ID'),
            'kaecredit_amount' => Yii::t('app', 'Kaecredit Amount'),
            'kaecredit_date' => Yii::t('app', 'Kaecredit Date'),
            'kaecredit_updated' => Yii::t('app', 'Kaecredit Updated'),
            'year' => Yii::t('app', 'Year'),
            'kae_id' => Yii::t('app', 'Kae ID'),
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
}
