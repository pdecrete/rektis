<?php

namespace app\modules\disposal\models;

use app\models\Teacher;
use app\models\User;
use app\modules\schooltransport\models\Schoolunit;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%disposal_disposal}}".
 *
 * @property integer $disposal_id
 * @property string $disposal_startdate
 * @property string $disposal_enddate
 * @property integer $disposal_hours
 * @property string $disposal_action
 * @property string $created_at
 * @property string $updated_at
 * @property integer $deleted
 * @property integer $archived 
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $teacher_id
 * @property integer $school_id
 * @property integer $disposalreason_id 
 * @property integer $disposalworkobj_id  
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property Teacher $teacher
 * @property Schoolunit $school
 * @property DisposalDisposalreason $disposalreason 
 * @property DisposalDisposalworkobj $disposalworkobj 
 * @property DisposalDisposalapproval[] $disposalDisposalapprovals
 * @property DisposalApproval[] $approvals
 */
class Disposal extends \yii\db\ActiveRecord
{
    public $disposal_endofteachingyear_flag = 1;
    
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
        return '{{%disposal_disposal}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['disposal_startdate', 'disposal_hours', 'disposal_action', 'teacher_id', 'school_id', 'disposalreason_id'], 'required'],
            [['disposal_startdate', 'disposal_enddate', 'created_at', 'updated_at'], 'safe'],
            [['disposal_hours', 'deleted', 'archived', 'created_by', 'updated_by', 'teacher_id', 'school_id', 'disposalreason_id', 'disposalworkobj_id'], 'integer'],
            [['disposal_action'], 'string', 'max' => 200],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => Teacher::className(), 'targetAttribute' => ['teacher_id' => 'teacher_id']],
            [['school_id'], 'exist', 'skipOnError' => true, 'targetClass' => Schoolunit::className(), 'targetAttribute' => ['school_id' => 'school_id']],
            [['disposalreason_id'], 'exist', 'skipOnError' => true, 'targetClass' => DisposalReason::className(), 'targetAttribute' => ['disposalreason_id' => 'disposalreason_id']],
            [['disposalworkobj_id'], 'exist', 'skipOnError' => true, 'targetClass' => DisposalWorkobj::className(), 'targetAttribute' => ['disposalworkobj_id' => 'disposalworkobj_id']], 
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'disposal_id' => Yii::t('app', 'Disposal ID'),
            'disposal_startdate' => Yii::t('app', 'Έναρξη Διάθεσης'),
            'disposal_enddate' => Yii::t('app', 'Λήξη Διάθεσης'),
            'disposal_hours' => Yii::t('app', 'Ώρες Διάθεσης'),
            'disposal_action' => Yii::t('app', 'Πράξη Διάθεσης'),
            'disposal_created_at' => Yii::t('app', 'Ημ/νία Δημιουργίας'),
            'disposal_updated_at' => Yii::t('app', 'Ημ/νία Επεξεργασίας'),
            'deleted' => Yii::t('app', 'Deleted'),
            'archived' => Yii::t('app', 'Archived'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'teacher_id' => Yii::t('app', 'Teacher ID'),
            'school_id' => Yii::t('app', 'School ID'),
            'disposalreason_id' => Yii::t('app', 'Disposalreason ID'),
            'disposalworkobj_id' => Yii::t('app', 'Disposalworkobj ID'),
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
    public function getTeacher()
    {
        return $this->hasOne(Teacher::className(), ['teacher_id' => 'teacher_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchool()
    {
        return $this->hasOne(Schoolunit::className(), ['school_id' => 'school_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDisposalreason()
    {
        return $this->hasOne(Disposalreason::className(), ['disposalreason_id' => 'disposalreason_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDisposalworkobj()
    {
        return $this->hasOne(Disposalworkobj::className(), ['disposalworkobj_id' => 'disposalworkobj_id']);
    }    
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDisposalapprovals()
    {
        return $this->hasMany(DisposalApproval::className(), ['disposal_id' => 'disposal_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApprovals()
    {
        return $this->hasMany(DisposalApproval::className(), ['approval_id' => 'approval_id'])->viaTable('{{%disposal_disposalapproval}}', ['disposal_id' => 'disposal_id']);
    }
    
    /**
     * @return array
     */
    public static function getHourOptions()
    {
        $disposal_hours[-1] = array("hours" => -1, "hours_name" => "Ολική Διάθεση");
        for ($i = 1; $i <= 24; $i++)
            $disposal_hours[$i] = array("hours" => $i, "hours_name" => $i);
        return $disposal_hours;
    }

    /**
     * @inheritdoc
     * @return DisposalQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DisposalQuery(get_called_class());
    }
}
