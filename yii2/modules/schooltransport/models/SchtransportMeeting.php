<?php

namespace app\modules\schooltransport\models;

use app\modules\schooltransport\Module;
use Yii;

/**
 * This is the model class for table "{{%schtransport_meeting}}".
 *
 * @property integer $meeting_id
 * @property string $meeting_city
 * @property string $meeting_country
 * @property string $meeting_startdate
 * @property string $meeting_enddate
 * @property integer $program_id
 *
 * @property SchtransportProgram $program
 * @property SchtransportTransport[] $schtransportTransports
 */
class SchtransportMeeting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%schtransport_meeting}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['meeting_city', 'meeting_country', 'program_id'], 'required'],
            [['meeting_startdate', 'meeting_enddate'], 'safe'],
            [['program_id'], 'integer'],
            [['meeting_city', 'meeting_country'], 'string', 'max' => 100],
            [['meeting_hostschool'], 'string', 'max' => 200],
            [['program_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchtransportProgram::className(), 'targetAttribute' => ['program_id' => 'program_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'meeting_id' => Module::t('modules/schooltransport/app', 'Meeting ID'),
            'meeting_city' => Module::t('modules/schooltransport/app', 'City'),
            'meeting_country' => Module::t('modules/schooltransport/app', 'Country'),
            'meeting_hostschool' => Module::t('modules/schooltransport/app', 'Hosting School'),
            'meeting_startdate' => Module::t('modules/schooltransport/app', 'Meeting Start'),
            'meeting_enddate' => Module::t('modules/schooltransport/app', 'Meeting End'),
            'program_id' => Module::t('modules/schooltransport/app', 'Program ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgram()
    {
        return $this->hasOne(SchtransportProgram::className(), ['program_id' => 'program_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchtransportTransports()
    {
        return $this->hasMany(SchtransportTransport::className(), ['meeting_id' => 'meeting_id']);
    }

    /**
     * @inheritdoc
     * @return SchtransportMeetingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SchtransportMeetingQuery(get_called_class());
    }
}
