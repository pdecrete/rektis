<?php

namespace app\modules\disposal\models;

use app\modules\eduinventory\models\Teacher;
use app\models\User;
use app\modules\disposal\DisposalModule;
use app\modules\schooltransport\models\Schoolunit;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use app\modules\schooltransport\models\Directorate;

/**
 * This is the model class for table "{{%disposal_disposal}}".
 *
 * @property integer $disposal_id
 * @property string $disposal_startdate
 * @property string $disposal_enddate
 * @property integer $disposal_hours
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
 * @property integer $localdirdecision_id
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property Teacher $teacher
 * @property Schoolunit $school
 * @property DisposalDisposalreason $disposalreason 
 * @property DisposalDisposalworkobj $disposalworkobj
 * @property DisposalLocaldirdecision $localdirdecision 
 * @property DisposalDisposalapproval[] $disposalDisposalapprovals
 * @property DisposalApproval[] $approvals
 */
class Disposal extends \yii\db\ActiveRecord
{
    const FULL_DISPOSAL = -1;
    
    public $disposal_endofteachingyear_flag = 0;
    
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
            [['disposal_startdate', 'disposal_hours', 'teacher_id', 'school_id', 'disposalreason_id'], 'required'],
            [['disposal_startdate', 'disposal_enddate', 'created_at', 'updated_at'], 'safe'],
            [['disposal_hours', 'disposal_republished' ,'deleted', 'archived', 'created_by', 'updated_by', 'teacher_id', 'school_id', 'disposalreason_id', 'disposalworkobj_id', 'localdirdecision_id'], 'integer'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => Teacher::className(), 'targetAttribute' => ['teacher_id' => 'teacher_id']],
            [['school_id'], 'exist', 'skipOnError' => true, 'targetClass' => Schoolunit::className(), 'targetAttribute' => ['school_id' => 'school_id']],
            [['disposalreason_id'], 'exist', 'skipOnError' => true, 'targetClass' => DisposalReason::className(), 'targetAttribute' => ['disposalreason_id' => 'disposalreason_id']],
            [['disposalworkobj_id'], 'exist', 'skipOnError' => true, 'targetClass' => DisposalWorkobj::className(), 'targetAttribute' => ['disposalworkobj_id' => 'disposalworkobj_id']], 
            [['localdirdecision_id'], 'exist', 'skipOnError' => true, 'targetClass' => DisposalLocaldirdecision::className(), 'targetAttribute' => ['localdirdecision_id' => 'localdirdecision_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'disposal_id' => DisposalModule::t('modules/disposal/app', 'Disposal ID'),
            'disposal_startdate' => DisposalModule::t('modules/disposal/app', 'Έναρξη Διάθεσης'),
            'disposal_enddate' => DisposalModule::t('modules/disposal/app', 'Λήξη Διάθεσης'),
            'disposal_hours' => DisposalModule::t('modules/disposal/app', 'Ώρες Διάθεσης'),
            'disposal_republished' => DisposalModule::t('modules/disposal/app', 'Ανακοινοποιημένη Διάθεση'),
            'disposal_created_at' => DisposalModule::t('modules/disposal/app', 'Ημ/νία Δημιουργίας'),
            'disposal_updated_at' => DisposalModule::t('modules/disposal/app', 'Ημ/νία Επεξεργασίας'),
            'deleted' => DisposalModule::t('modules/disposal/app', 'Deleted'),
            'archived' => DisposalModule::t('modules/disposal/app', 'Archived'),
            'created_by' => DisposalModule::t('modules/disposal/app', 'Created By'),
            'updated_by' => DisposalModule::t('modules/disposal/app', 'Updated By'),
            'teacher_id' => DisposalModule::t('modules/disposal/app', 'Teacher'),
            'school_id' => DisposalModule::t('modules/disposal/app', 'Disposal School'),
            'disposalreason_id' => DisposalModule::t('modules/disposal/app', 'Disposal Reason'),
            'disposalworkobj_id' => DisposalModule::t('modules/disposal/app', 'Disposal Working Object'),
            'localdirdecision_id' => DisposalModule::t('modules/disposal/app', 'Local Directorate'),
        ];
    }
    
    public function isForHealthReasons()
    {
        return ($this->disposalreason_id == DisposalReason::findOne(['disposalreason_name' => DisposalReason::HEALTH_REASONS])['disposalreason_id']);
    }
    
    /**
     * Returns the Directorate of the disposal school.
     * 
     * @return \app\modules\schooltransport\models\Directorate|array|NULL
     */
    public function getDirectorate()
    {
        return Directorate::find()->where(['directorate_id' => $this->getSchool()->one()['directorate_id']])->one();
    }

    /**
     * Returns the Directorate of the disposal's teacher.
     *
     * @return \app\modules\schooltransport\models\Directorate|array|NULL
     */
    public function getTeacherDirectorate()
    {
        $teacher_school_id = Schoolunit::findOne(['school_id' => $this->getTeacher()->one()['school_id']]);
        return Directorate::find()->where(['directorate_id' => $teacher_school_id])->one();
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
        return $this->hasOne(DisposalReason::className(), ['disposalreason_id' => 'disposalreason_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDisposalworkobj()
    {
        return $this->hasOne(DisposalWorkobj::className(), ['disposalworkobj_id' => 'disposalworkobj_id']);
    }    
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocaldirdecision()
    {
        return $this->hasOne(DisposalLocaldirdecision::className(), ['localdirdecision_id' => 'localdirdecision_id']);
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
        $disposal_hours[Disposal::FULL_DISPOSAL] = array("hours" => Disposal::FULL_DISPOSAL, "hours_name" => "Ολική Διάθεση");
        for ($i = 1; $i <= 24; $i++)
            $disposal_hours[$i] = array("hours" => $i, "hours_name" => $i);
        return $disposal_hours;
    }
    
    /**
     * @param integer $school_year
     * @return \yii\db\ActiveQuery
     */
    public static function getSchoolYearDisposals($school_year = -1)
    {
        $tblprefix = Yii::$app->db->tablePrefix;
        $t = $tblprefix . 'disposal_disposal';
        $query = DisposalSearch::getAllDisposalsQuery(1);//$archived = 1 in argument
        
        if ($school_year != -1) {
            $query = $query->andWhere($t . ".disposal_startdate >= '" . $school_year . "-09-01' AND " .
                $t . ".disposal_startdate <= '" . (string)($school_year+1) . "-08-31'");
        }
        return $query->all();
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
