<?php

namespace app\modules\finance\models;

use Yii;

/**
 * This is the model class for table "{{%finance_kae}}".
 *
 * @property integer $kae_id
 * @property string $kae_title
 * @property string $kae_description
 *
 * @property FinanceKaecredit[] $financeKaecredits
 */
class KAE extends \yii\db\ActiveRecord
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
            [['kae_title'], 'required'],
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
            'kae_id' => Yii::t('app', 'Kae ID'),
            'kae_title' => Yii::t('app', 'Kae Title'),
            'kae_description' => Yii::t('app', 'Kae Description'),
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
     * @inheritdoc
     * @return KAEQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KAEQuery(get_called_class());
    }
}
