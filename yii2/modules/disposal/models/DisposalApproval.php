<?php

namespace app\modules\disposal\models;

use Yii;

/**
 * This is the model class for table "{{%disposal_approval}}".
 *
 * @property integer $approval_id
 * @property string $approval_regionaldirectprotocol
 * @property string $approval_localdirectprotocol
 * @property string $approval_notes
 * @property string $approval_file
 * @property string $approval_signedfile
 * @property string $approval_created_at
 * @property string $approval_updated_at
 * @property integer $approval_created_by
 * @property integer $approval_updated_by
 *
 * @property DisposalDisposalapproval[] $disposalDisposalapprovals
 * @property DisposalDisposal[] $disposals
 */
class DisposalApproval extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%disposal_approval}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['approval_regionaldirectprotocol', 'approval_localdirectprotocol', 'approval_notes', 'approval_file', 'approval_signedfile', 'approval_created_by', 'approval_updated_by'], 'required'],
            [['approval_created_at', 'approval_updated_at'], 'safe'],
            [['approval_created_by', 'approval_updated_by'], 'integer'],
            [['approval_regionaldirectprotocol', 'approval_localdirectprotocol'], 'string', 'max' => 100],
            [['approval_notes'], 'string', 'max' => 400],
            [['approval_file', 'approval_signedfile'], 'string', 'max' => 300],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'approval_id' => Yii::t('app', 'Approval ID'),
            'approval_regionaldirectprotocol' => Yii::t('app', 'Πρωτόκολλο ΠΔΕ'),
            'approval_localdirectprotocol' => Yii::t('app', 'Πρωτόκολλο Διεύθυνσης Σχολείου'),
            'approval_notes' => Yii::t('app', 'Σημειώσεις'),
            'approval_file' => Yii::t('app', 'Αρχείο Έγκρισης'),
            'approval_signedfile' => Yii::t('app', 'Ψηφιακά Υπογεγραμμένο Αρχείο Έγκρισης'),
            'approval_created_at' => Yii::t('app', 'Ημ/νία Δημιουργίας'),
            'approval_updated_at' => Yii::t('app', 'Ημ/νία Επεξεργασίας'),
            'approval_created_by' => Yii::t('app', 'Approval Created By'),
            'approval_updated_by' => Yii::t('app', 'Approval Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDisposalDisposalapprovals()
    {
        return $this->hasMany(DisposalDisposalapproval::className(), ['approval_id' => 'approval_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDisposals()
    {
        return $this->hasMany(Disposal::className(), ['disposal_id' => 'disposal_id'])->viaTable('{{%disposal_disposalapproval}}', ['approval_id' => 'approval_id']);
    }

    /**
     * @inheritdoc
     * @return DisposalApprovalQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DisposalApprovalQuery(get_called_class());
    }
}
