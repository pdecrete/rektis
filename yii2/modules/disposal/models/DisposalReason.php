<?php

namespace app\modules\disposal\models;

use Yii;

/**
 * This is the model class for table "{{%disposal_disposalreason}}".
 *
 * @property integer $disposalreason_id
 * @property string $disposalreason_name
 * @property string $disposalreason_description
 *
 * @property DisposalDisposal[] $disposalDisposals
 */
class DisposalReason extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%disposal_disposalreason}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['disposalreason_name', 'disposalreason_description'], 'required'],
            [['disposalreason_name'], 'string', 'max' => 50],
            [['disposalreason_description'], 'string', 'max' => 200],
            [['disposalreason_name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'disposalreason_id' => Yii::t('app', 'Disposalreason ID'),
            'disposalreason_name' => Yii::t('app', 'Λεκτικό Αναγνωριστικό'),
            'disposalreason_description' => Yii::t('app', 'Λόγος Διάθεσης'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDisposalDisposals()
    {
        return $this->hasMany(DisposalDisposal::className(), ['disposalreason_id' => 'disposalreason_id']);
    }

    /**
     * @inheritdoc
     * @return DisposalReasonQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DisposalReasonQuery(get_called_class());
    }
}
