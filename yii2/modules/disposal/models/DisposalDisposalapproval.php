<?php

namespace app\modules\disposal\models;

use Yii;

/**
 * This is the model class for table "{{%disposal_disposalapproval}}".
 *
 * @property integer $disposal_id
 * @property integer $approval_id
 *
 * @property DisposalDisposal $disposal
 * @property DisposalApproval $approval
 */
class DisposalDisposalapproval extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%disposal_disposalapproval}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['disposal_id', 'approval_id'], 'required'],
            [['disposal_id', 'approval_id'], 'integer'],
            [['disposal_id'], 'exist', 'skipOnError' => true, 'targetClass' => DisposalDisposal::className(), 'targetAttribute' => ['disposal_id' => 'disposal_id']],
            [['approval_id'], 'exist', 'skipOnError' => true, 'targetClass' => DisposalApproval::className(), 'targetAttribute' => ['approval_id' => 'approval_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'disposal_id' => Yii::t('app', 'Disposal ID'),
            'approval_id' => Yii::t('app', 'Approval ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDisposal()
    {
        return $this->hasOne(DisposalDisposal::className(), ['disposal_id' => 'disposal_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApproval()
    {
        return $this->hasOne(DisposalApproval::className(), ['approval_id' => 'approval_id']);
    }

    /**
     * @inheritdoc
     * @return DisposalDisposalapprovalQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DisposalDisposalapprovalQuery(get_called_class());
    }
}
