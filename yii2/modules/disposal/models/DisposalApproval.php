<?php

namespace app\modules\disposal\models;

use app\models\User;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%disposal_approval}}".
 *
 * @property integer $approval_id
 * @property string $approval_regionaldirectprotocol
 * @property date $approval_regionaldirectprotocoldate
 * @property string $approval_notes
 * @property string $approval_file
 * @property integer $approval_type
 * @property string $approval_signedfile
 * @property integer $approval_republished
 * @property string $approval_republishedtext
 * @property date $approval_republisheddate
 * @property integer $deleted
 * @property integer $archived
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property DisposalDisposalapproval[] $disposalDisposalapprovals
 * @property DisposalDisposal[] $disposals
 */
class DisposalApproval extends \yii\db\ActiveRecord
{
    const DISPOSALS_APPROVAL_GENERAL = 1;
    const COMMON_SPECIALIZATIONS_DECISION = 2;
    const EUROPEAN_SCHOOL_DECISION = 3;
    
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_by', 'updated_by'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_by',
                ],
                'value' => Yii::$app->user->identity->getId()
            ],
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }


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
            [['approval_type', 'approval_regionaldirectprotocol', 'approval_regionaldirectprotocoldate', 'approval_file', 'approval_signedfile'], 'required'],
            [['created_at', 'updated_at', 'approval_regionaldirectprotocoldate', 'approval_republishdate'], 'safe'],
            [['approval_type', 'approval_republished', 'deleted', 'archived', 'created_by', 'updated_by'], 'integer'],
            [['approval_regionaldirectprotocol'], 'string', 'max' => 100],
            [['approval_notes'], 'string', 'max' => 500],
            [['approval_republishtext'], 'string', 'max' => 2000],
            [['approval_file', 'approval_signedfile'], 'string', 'max' => 300],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'approval_id' => Yii::t('app', 'Α/Α'),
            'approval_regionaldirectprotocol' => Yii::t('app', 'Πρωτόκολλο ΠΔΕ'),
            'approval_regionaldirectprotocoldate' => Yii::t('app', 'Ημερομηνία Πρωτοκόλλου ΠΔΕ'),
            'approval_notes' => Yii::t('app', 'Σημειώσεις'),
            'approval_file' => Yii::t('app', 'Αρχείο Έγκρισης'),
            'approval_signedfile' => Yii::t('app', 'Ψηφιακά Υπογεγραμμένο Αρχείο Έγκρισης'),
            'approval_type' => Yii::t('app', 'Τύπος Εγγράφου'),
            'deleted' => Yii::t('app', 'Deleted'),
            'archived' => Yii::t('app', 'Archived'),
            'approval_republished' => Yii::t('app', 'Ανακοινοποίηση Έγκρισης'),
            'approval_republishtext' => Yii::t('app', 'Ανακοινοποίηση ως προς'),
            'approval_republishdate' => Yii::t('app', 'Ημερομηνία Ανακοινοποίησης'),
            'created_at' => Yii::t('app', 'Ημ/νία Δημιουργίας'),
            'updated_at' => Yii::t('app', 'Ημ/νία Επεξεργασίας'),
            'created_by' => Yii::t('app', 'Approval Created By'),
            'updated_by' => Yii::t('app', 'Approval Updated By'),
        ];
    }
    
    public static function getTypeOptions()
    {
        return [DisposalApproval::DISPOSALS_APPROVAL_GENERAL => 'Έγκριση', 
                DisposalApproval::COMMON_SPECIALIZATIONS_DECISION => 'Απόφαση για κοινές ειδικότητες', 
                DisposalApproval::EUROPEAN_SCHOOL_DECISION => 'Απόφαση για Σχολείο Ευρωπαϊκής Παιδείας'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
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
     * If $this approval is a republish, then it returns the initial approval, otherwise returns null
     * 
     * @return \app\modules\disposal\models\DisposalApproval
     */
    public function getRepublishedApproval() 
    {
        if($this->approval_id == null)
            return null;
        return DisposalApproval::findOne(['approval_republished' => $this->approval_id]);
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
