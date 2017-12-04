<?php

namespace app\modules\finance\models;

use Yii;

/**
 * This is the model class for table "{{%finance_kaecreditpercentage}}".
 *
 * @property integer $kaeperc_id
 * @property string $kaeperc_percentage
 * @property string $kaeperc_date
 * @property string $kaeperc_decision
 * @property integer $kaecredit_id
 *
 * @property FinanceKaecredit $kaecredit
 */
class FinanceKaecreditpercentage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_kaecreditpercentage}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kaeperc_percentage', 'kaeperc_date', 'kaecredit_id'], 'required'],
            //[['kaeperc_percentage'], 'number'],
            [['kaeperc_date'], 'safe'],
            [['kaecredit_id'], 'integer'],
            [['kaeperc_decision'], 'string', 'max' => 255],
            [['kaecredit_id'], 'exist', 'skipOnError' => true, 'targetClass' => FinanceKaecredit::className(), 'targetAttribute' => ['kaecredit_id' => 'kaecredit_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kaeperc_id' => Yii::t('app', 'Kaeperc ID'),
            'kaeperc_percentage' => Yii::t('app', 'Kaeperc Percentage'),
            'kaeperc_date' => Yii::t('app', 'Kaeperc Date'),
            'kaeperc_decision' => Yii::t('app', 'Kaeperc Decision'),
            'kaecredit_id' => Yii::t('app', 'Kaecredit ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKaecredit()
    {
        return $this->hasOne(FinanceKaecredit::className(), ['kaecredit_id' => 'kaecredit_id']);
    }
    
    public function getKae()
    {
        return $this->hasOne(FinanceKae::className(),
            ['kae_id' => 'kae_id'])->viaTable(Yii::$app->db->tablePrefix . 'finance_kaecredit', ['kaecredit_id' => 'kaecredit_id']);
    }
    
    /**
     * @inheritdoc
     * @return FinanceKaecreditpercentageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FinanceKaecreditpercentageQuery(get_called_class());
    }
    
    public static function getKaeCreditSumPercentage($kaecredit)
    {
        return FinanceKaecreditpercentage::find()->where(['kaecredit_id' => $kaecredit])->sum("kaeperc_percentage");
    }
}
