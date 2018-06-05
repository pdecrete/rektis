<?php

namespace app\modules\schooltransport\models;

use app\modules\schooltransport\Module;
use Yii;

/**
 * This is the model class for table "{{%schtransport_transport}}".
 *
 * @property integer $transport_id
 * @property string $transport_submissiondate
 * @property string $transport_startdate
 * @property string $transport_enddate
 * @property string $transport_teachers
 * @property string $transport_students
 * @property integer $meeting_id
 * @property integer $school_id
 *
 * @property SchtransportMeeting $meeting
 * @property Schoolunit $school
 */
class SchtransportTransport extends \yii\db\ActiveRecord
{
    public $signedfile;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%schtransport_transport}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transport_submissiondate', 'transport_startdate', 'transport_enddate', 'transport_datesentapproval', 'transport_creationdate'], 'safe'],
            [['transport_startdate', 'transport_creationdate', 'transport_enddate', 'transport_teachers', 'transport_localdirectorate_protocol', 'meeting_id', 'school_id'], 'required'],
            [['meeting_id', 'school_id'], 'integer'],
            [['transport_headteacher'], 'string', 'max' => 100],
            [['transport_teachers'], 'string', 'max' => 1000],
            [['transport_students'], 'string', 'max' => 2000],
            [['transport_class'], 'string', 'max' => 10],
            [['transport_schoolrecord'], 'string', 'max' => 200],
            [['transport_localdirectorate_protocol', 'transport_pde_protocol', 'transport_dateprotocolcompleted'], 'string', 'max' => 100],
            [['transport_remarks'], 'string', 'max' => 500],
            [['transport_approvalfile', 'transport_signedapprovalfile'], 'string', 'max' => 200],
            [['transport_isarchived'], 'integer'],
            [['meeting_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchtransportMeeting::className(), 'targetAttribute' => ['meeting_id' => 'meeting_id']],
            [['school_id'], 'exist', 'skipOnError' => true, 'targetClass' => Schoolunit::className(), 'targetAttribute' => ['school_id' => 'school_id']],
            [['signedfile'], 'safe'], //----
            [['signedfile'], 'file', 'extensions'=>'pdf'], //----
            [['signedfile'], 'file', 'maxSize'=>'10000000'], //----
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'transport_id' => Module::t('modules/schooltransport/app', 'Transport ID'),
            'transport_submissiondate' => Module::t('modules/schooltransport/app', 'Application Date'),
            'transport_startdate' => Module::t('modules/schooltransport/app', 'Transportation Start'),
            'transport_enddate' => Module::t('modules/schooltransport/app', 'Transportation End'),
            'transport_creationdate' => Module::t('modules/schooltransport/app', 'Creation Date'),
            'transport_headteacher' => Module::t('modules/schooltransport/app', 'Head Teacher'),
            'transport_teachers' => Module::t('modules/schooltransport/app', 'Transportation Teachers'),
            'transport_students' => Module::t('modules/schooltransport/app', 'Transportation Students'),
            'transport_class' => Module::t('modules/schooltransport/app', 'Class'),
            'transport_schoolrecord' => Module::t('modules/schooltransport/app', 'School Record'),
            'transport_localdirectorate_protocol' => Module::t('modules/schooltransport/app', 'School Directorate Protocol'),
            'transport_pde_protocol' => Module::t('modules/schooltransport/app', 'Approval Document Protocol'),
            'transport_remarks' => Module::t('modules/schooltransport/app', 'Remarks'),
            'transport_datesentapproval' => Module::t('modules/schooltransport/app', 'Approval Sent Date'),
            'transport_dateprotocolcompleted' => Module::t('modules/schooltransport/app', 'Registration Completion Protocol'),
            'transport_approvalfile' => Module::t('modules/schooltransport/app', 'Approval File'),
            'transport_signedapprovalfile' => Module::t('modules/schooltransport/app', 'Digital Signed File'),
            'transport_isarchived' => Module::t('modules/schooltransport/app', 'Archived Transport'),
            'meeting_id' => Module::t('modules/schooltransport/app', 'Meeting ID'),
            'school_id' => Module::t('modules/schooltransport/app', 'School ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMeeting()
    {
        return $this->hasOne(SchtransportMeeting::className(), ['meeting_id' => 'meeting_id']);
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
    public function getTransportstates()
    {
        return $this->hasMany(SchtransportTransportstate::className(), ['transport_id' => 'transport_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStates()
    {
        return $this->hasMany(SchtransportState::className(), ['state_id' => 'state_id'])->viaTable('{{%schtransport_transportstate}}', ['transport_id' => 'transport_id']);
    } 
 
    /**
     * Uploads the digitally signed file to the server.
     */
    public function upload($filename)
    {   //echo Yii::getAlias(Yii::$app->params['finance_uploadfolder']); die();
        
        if ($this->validate()) {
            //echo Yii::getAlias(Yii::$app->controller->module->params['schooltransport_uploadfolder']) . $filename;die();
            if(!isset($this->signedfile)){//echo "Hallo"; die();
                $this->transport_signedapprovalfile = null;
                return true;
            }
            //echo Yii::getAlias(Yii::$app->controller->module->params['schooltransport_uploadfolder']); die();
            $path = Yii::getAlias(Yii::$app->controller->module->params['schooltransport_uploadfolder']);
            if(!is_writeable($path))
                return false;            
            if(empty($this->signedfile->saveAs($path . $filename)))
                return false;
            
            $this->transport_signedapprovalfile = $filename;
            return true;
        } 
        else {
            return false;
        }
    }

    /**
     * @inheritdoc
     * @return SchtransportTransportQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SchtransportTransportQuery(get_called_class());
    }
}
