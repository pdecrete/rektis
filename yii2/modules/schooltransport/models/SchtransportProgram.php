<?php

namespace app\modules\schooltransport\models;

use Yii;

/**
 * This is the model class for table "{{%schtransport_program}}".
 *
 * @property integer $program_id
 * @property string $program_title
 * @property string $program_code
 * @property integer $programcategory_id
 *
 * @property SchtransportMeeting[] $schtransportMeetings
 * @property SchtransportProgramcategory $programcategory
 */
class SchtransportProgram extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%schtransport_program}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['program_title', 'programcategory_id'], 'required'],
            [['programcategory_id'], 'integer'],
            [['program_title'], 'string', 'max' => 300],
            [['program_code'], 'string', 'max' => 100],
            [['program_code'], 'unique'],
            [['programcategory_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchtransportProgramcategory::className(), 'targetAttribute' => ['programcategory_id' => 'programcategory_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'program_id' => Yii::t('app', 'Program ID'),
            'program_title' => Yii::t('app', 'Τίτλος Προγράμματος'),
            'program_code' => Yii::t('app', 'Κωδικός Προγράμματος'),
            'programcategory_id' => Yii::t('app', 'Programcategory ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchtransportMeetings()
    {
        return $this->hasMany(SchtransportMeeting::className(), ['program_id' => 'program_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgramcategory()
    {
        return $this->hasOne(SchtransportProgramcategory::className(), ['programcategory_id' => 'programcategory_id']);
    }

    /**
     * @inheritdoc
     * @return SchtransportProgramQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SchtransportProgramQuery(get_called_class());
    }
}
