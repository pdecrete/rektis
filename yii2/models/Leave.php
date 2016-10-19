<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%leave}}".
 *
 * @property integer $id
 * @property integer $employee
 * @property integer $type
 * @property integer $decision_protocol
 * @property string $decision_protocol_date
 * @property integer $application_protocol
 * @property string $application_protocol_date
 * @property string $application_date
 * @property string $accompanying_document
 * @property integer $duration
 * @property string $start_date
 * @property string $end_date
 * @property string $reason
 * @property string $comment
 * @property string $create_ts
 * @property string $update_ts
 * @property integer $deleted
 *
 * @property Employee $employeeObj
 * @property LeaveType $typeObj
 * @property LeavePrint[] $leavePrints 
 * @property Leave[] $leavesSameDecision 
 */
class Leave extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%leave}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'create_ts',
                'updatedAtAttribute' => 'update_ts',
                'value' => new Expression('NOW()')
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employee', 'type', 'decision_protocol', 'application_protocol', 'duration', 'deleted'], 'integer'],
            [['employee', 'type', 'decision_protocol', 'decision_protocol_date', 'application_protocol', 'application_protocol_date', 'application_date', 'duration', 'start_date', 'end_date'], 'required'],
            [['decision_protocol_date', 'application_protocol_date', 'application_date', 'start_date', 'end_date', 'create_ts', 'update_ts'], 'safe'],
            [['comment'], 'string'],
            [['accompanying_document'], 'string', 'max' => 100],
            [['reason'], 'string', 'max' => 200],
            [['employee'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['employee' => 'id']],
            [['type'], 'exist', 'skipOnError' => true, 'targetClass' => LeaveType::className(), 'targetAttribute' => ['type' => 'id']],
            [['type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'employee' => Yii::t('app', 'Employee'),
            'type' => Yii::t('app', 'Leave type'),
            'decision_protocol' => Yii::t('app', 'Decision protocol'),
            'decision_protocol_date' => Yii::t('app', 'Decision date'),
            'application_protocol' => Yii::t('app', 'Application protocol'),
            'application_protocol_date' => Yii::t('app', 'Protocol date'),
            'application_date' => Yii::t('app', 'Application date'),
            'accompanying_document' => Yii::t('app', 'Accompanying documents (certifications, etc.'),
            'duration' => Yii::t('app', 'Duration in days'),
            'start_date' => Yii::t('app', 'Start date'),
            'end_date' => Yii::t('app', 'End date'),
            'reason' => Yii::t('app', 'Reason (for special leaves etc.)'),
            'comment' => Yii::t('app', 'Comments'),
            'deleted' => Yii::t('app', 'Deleted'),
            'create_ts' => Yii::t('app', 'Create Ts'),
            'update_ts' => Yii::t('app', 'Update Ts'),
        ];
    }

    /**
     * @return String Leave info str
     */
    public function getInformation()
    {
        return ($this->employeeObj ? $this->employeeObj->fullname : Yii::t('app', 'UNKNOWN'))
                . ' (' . ($this->typeObj ? $this->typeObj->name : Yii::t('app', 'UNKNOWN'))
                . ') ' . Yii::$app->formatter->asDate($this->start_date, 'short')
                . '-' . Yii::$app->formatter->asDate($this->end_date, 'short')
                . '';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeObj()
    {
        return $this->hasOne(Employee::className(), ['id' => 'employee']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTypeObj()
    {
        return $this->hasOne(LeaveType::className(), ['id' => 'type']);
    }

    /**
     * @return \yii\db\ActiveQuery 
     */
    public function getLeavePrints()
    {
        return $this->hasMany(LeavePrint::className(), ['leave' => 'id']);
    }

    public function allSameDecision()
    {
        return Leave::find()
                        ->where([
                            'decision_protocol' => $this->decision_protocol,
                            'decision_protocol_date' => $this->decision_protocol_date
                        ])
                        ->orderBy('id')
                        ->all();
    }

	/**
	 * @return distinct all emails (employee, service_organic) for all employees of decision-date
	 * deletes duplicate values
	 * deletes empty strings
	 **/
	public function getDecisionEmails()
	{	
		$emails = [];
		$sameDecisionModels = $this->allSameDecision();
        $all_count = count($sameDecisionModels);
        for ($c = 0; $c < $all_count; $c++) {
            $currentModel = $sameDecisionModels[$c];	
            $emails[$c*3] = $currentModel->employeeObj->email;
			$emails[$c*3+1] = $currentModel->employeeObj->serviceOrganic->email;
			$emails[$c*3+2] = $currentModel->employeeObj->serviceServe->email;
        }
        $num = count($emails);
        $k = 0;
        $final_emails = [];
        for ($i = 0; $i < $num; $i++) {
			if (($emails[$i] !== '') && ($emails[$i] !== ' ')) {
				$final_emails[$k] = $emails[$i];
				$k++;
			}
		}	    
        $dist_emails = array_unique($final_emails);      
        $dist_emails = array_diff($dist_emails, ['']);      
        return $dist_emails;
	}

	/**
	 * @return connected Leave IDs for all employees of decision-date
	 **/
	public function getconnectedLeaveIDs()
	{	
		$IDs = [];
		$k = 0; 
		$sameDecisionModels = $this->allSameDecision();
        $all_count = count($sameDecisionModels);
        for ($c = 0; $c < $all_count; $c++) {
            $currentModel = $sameDecisionModels[$c];	
			$IDs[$k] = $currentModel->id;
			$k++;
        }
        return $IDs;
	}
	
    /**
     * @inheritdoc
     * @return LeaveQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LeaveQuery(get_called_class());
    }

}
