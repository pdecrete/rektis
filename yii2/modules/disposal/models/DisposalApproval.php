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
 * @property string $approval_localdirectprotocol
 * @property string $approval_notes
 * @property string $approval_file
 * @property string $approval_signedfile
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
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
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
            [['approval_regionaldirectprotocol', 'approval_localdirectprotocol', 'approval_notes', 'approval_file', 'approval_signedfile'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['deleted', 'archived', 'created_by', 'updated_by'], 'integer'],
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
            'deleted' => Yii::t('app', 'Deleted'),
            'archived' => Yii::t('app', 'Archived'),
            'created_at' => Yii::t('app', 'Ημ/νία Δημιουργίας'),
            'updated_at' => Yii::t('app', 'Ημ/νία Επεξεργασίας'),
            'created_by' => Yii::t('app', 'Approval Created By'),
            'updated_by' => Yii::t('app', 'Approval Updated By'),
        ];
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
     * @inheritdoc
     * @return DisposalApprovalQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DisposalApprovalQuery(get_called_class());
    }
}
