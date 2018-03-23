<?php

namespace app\modules\schooltransport\models;

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
            [['transport_startdate', 'transport_enddate', 'transport_teachers', 'meeting_id', 'school_id'], 'required'],
            [['transport_submissiondate', 'transport_startdate', 'transport_enddate'], 'safe'],
            [['meeting_id', 'school_id'], 'integer'],
            [['transport_teachers'], 'string', 'max' => 1000],
            [['transport_students'], 'string', 'max' => 2000],
            [['meeting_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchtransportMeeting::className(), 'targetAttribute' => ['meeting_id' => 'meeting_id']],
            [['school_id'], 'exist', 'skipOnError' => true, 'targetClass' => Schoolunit::className(), 'targetAttribute' => ['school_id' => 'school_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'transport_id' => Yii::t('app', 'Transport ID'),
            'transport_submissiondate' => Yii::t('app', 'Ημερομηνία Αίτησης Έγκρισης'),
            'transport_startdate' => Yii::t('app', 'Έναρξη Μετακίνησης'),
            'transport_enddate' => Yii::t('app', 'Λήξη Μετακίνησης'),
            'transport_teachers' => Yii::t('app', 'Μετακινούμενοι Εκπαιδευτικοί'),
            'transport_students' => Yii::t('app', 'Μετακινούμενοι Μαθητές'),
            'meeting_id' => Yii::t('app', 'Meeting ID'),
            'school_id' => Yii::t('app', 'School ID'),
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
