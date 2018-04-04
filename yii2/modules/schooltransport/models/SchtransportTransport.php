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
            [['transport_submissiondate', 'transport_startdate', 'transport_enddate', 'transport_datesentapproval'], 'safe'],
            [['transport_startdate', 'transport_enddate', 'transport_teachers', 'transport_localdirectorate_protocol', 'meeting_id', 'school_id'], 'required'],
            [['meeting_id', 'school_id'], 'integer'],
            [['transport_teachers'], 'string', 'max' => 1000],
            [['transport_students'], 'string', 'max' => 2000],
            [['transport_localdirectorate_protocol', 'transport_pde_protocol', 'transport_dateprotocolcompleted'], 'string', 'max' => 100],
            [['transport_remarks'], 'string', 'max' => 500],
            [['transport_approvalfile', 'transport_signedapprovalfile'], 'string', 'max' => 200],
            [['meeting_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchtransportMeeting::className(), 'targetAttribute' => ['meeting_id' => 'meeting_id']],
            [['school_id'], 'exist', 'skipOnError' => true, 'targetClass' => Schoolunit::className(), 'targetAttribute' => ['school_id' => 'school_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        /*return [
            'transport_id' => Module::t('modules/schooltransport/app', 'Transport ID'),
            'transport_submissiondate' => Module::t('modules/schooltransport/app', 'Application Date'),
            'transport_startdate' => Module::t('modules/schooltransport/app', 'Transportation Start'),
            'transport_enddate' => Module::t('modules/schooltransport/app', 'Transportation End'),
            'transport_teachers' => Module::t('modules/schooltransport/app', 'Transportation Teachers'),
            'transport_students' => Module::t('modules/schooltransport/app', 'Transportation Students'),
            'transport_localdirectorate_protocol' => Module::t('modules/schooltransport/app', 'Μετακινούμενοι Μαθητές'),
            'transport_pde_protocol' => Module::t('modules/schooltransport/app', 'Μετακινούμενοι Μαθητές'),
            'transport_remarks' => Module::t('modules/schooltransport/app', 'Μετακινούμενοι Μαθητές'),
            'transport_datesentapproval' => Module::t('modules/schooltransport/app', 'Ημερομηνία Αποστολής της Έγκρισης Μετακίνησης'),
            'transport_dateprotocolcompleted' => Module::t('modules/schooltransport/app', 'Ημερομηνία Ξεχρέωσης στο Πρωτόκολλο'),
            'transport_approvalfile' => Module::t('modules/schooltransport/app', 'Αρχείο Έγκρισης'),
            'transport_signedapprovalfile' => Module::t('modules/schooltransport/app', 'Αρχείο Ψηφιακά Υπογεγραμμένο'),
            'meeting_id' => Module::t('modules/schooltransport/app', 'Meeting ID'),
            'school_id' => Module::t('modules/schooltransport/app', 'School ID'),
        ];*/
        return [
            'transport_id' => Module::t('modules/schooltransport/app', 'Transport ID'),
            'transport_submissiondate' => Module::t('modules/schooltransport/app', 'Application Date'),
            'transport_startdate' => Module::t('modules/schooltransport/app', 'Transportation Start'),
            'transport_enddate' => Module::t('modules/schooltransport/app', 'Transportation End'),
            'transport_teachers' => Module::t('modules/schooltransport/app', 'Transportation Teachers'),
            'transport_students' => Module::t('modules/schooltransport/app', 'Transportation Students'),
            'transport_localdirectorate_protocol' => Module::t('modules/schooltransport/app', 'School Directorate Protocol'),
            'transport_pde_protocol' => Module::t('modules/schooltransport/app', 'Approval Document Protocol'),
            'transport_remarks' => Module::t('modules/schooltransport/app', 'Remarks'),
            'transport_datesentapproval' => Module::t('modules/schooltransport/app', 'Approval Sent Date'),
            'transport_dateprotocolcompleted' => Module::t('modules/schooltransport/app', 'Registration Completion Protocol'),
            'transport_approvalfile' => Module::t('modules/schooltransport/app', 'Approval File'),
            'transport_signedapprovalfile' => Module::t('modules/schooltransport/app', 'Digital Signed File'),
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
     * @inheritdoc
     * @return SchtransportTransportQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SchtransportTransportQuery(get_called_class());
    }
}
