<?php

namespace app\modules\disposal\models;

use Yii;

/**
 * This is the model class for table "{{%disposal_disposalworkobj}}".
 *
 * @property integer $disposalworkobj_id
 * @property string $disposalworkobj_name
 * @property string $disposalworkobj_description
 *
 * @property DisposalDisposal[] $disposalDisposals
 */
class DisposalWorkobj extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%disposal_disposalworkobj}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['disposalworkobj_name', 'disposalworkobj_description'], 'required'],
            [['disposalworkobj_name'], 'string', 'max' => 50],
            [['disposalworkobj_description'], 'string', 'max' => 200],
            [['disposalworkobj_name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'disposalworkobj_id' => Yii::t('app', 'Disposalworkobj ID'),
            'disposalworkobj_name' => Yii::t('app', 'Αντικείμενο Εργασίας Διάθεσης'),
            'disposalworkobj_description' => Yii::t('app', 'Αντικείμενο Εργασίας Διάθεσης'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDisposalDisposals()
    {
        return $this->hasMany(DisposalDisposal::className(), ['disposalworkobj_id' => 'disposalworkobj_id']);
    }

    /**
     * @inheritdoc
     * @return DisposalWorkobjQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DisposalWorkobjQuery(get_called_class());
    }
}
