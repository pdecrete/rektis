<?php

namespace app\modules\eduinventory\models;

use app\models\Specialisation;
use app\modules\disposal\models\Disposal;
use app\modules\disposal\models\DisposalLedger;
use app\modules\schooltransport\models\Schoolunit;
use Yii;

/**
 * This is the model class for table "{{%teacher}}".
 *
 * @property integer $teacher_id
 * @property string $teacher_surname
 * @property string $teacher_name
 * @property string $teacher_registrynumber
 * @property integer $specialisation_id
 * @property integer $school_id
 * @property integer $teacher_gender
 * @property string $teacher_fathername
 * @property string $teacher_mothername
 *
 * @property DisposalDisposal[] $disposalDisposals
 * @property DisposalLedger[] $disposalLedgers
 * @property Specialisation $specialisation
 * @property Schoolunit $school
 */
class Teacher extends \yii\db\ActiveRecord
{
    const FEMALE = 1;
    const MALE = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%teacher}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['teacher_surname', 'teacher_name', 'teacher_registrynumber', 'specialisation_id', 'school_id'], 'required'],
            [['specialisation_id', 'school_id', 'teacher_registrynumber', 'teacher_gender'], 'integer'],
            [['teacher_surname', 'teacher_name', 'teacher_fathername', 'teacher_mothername'], 'string', 'max' => 100],
            [['teacher_registrynumber'], 'unique'],
            [['specialisation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Specialisation::className(), 'targetAttribute' => ['specialisation_id' => 'id']],
            [['school_id'], 'exist', 'skipOnError' => true, 'targetClass' => Schoolunit::className(), 'targetAttribute' => ['school_id' => 'school_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'teacher_id' => Yii::t('app', 'Teacher ID'),
            'teacher_surname' => Yii::t('app', 'Επίθετο'),
            'teacher_name' => Yii::t('app', 'Όνομα'),
            'teacher_registrynumber' => Yii::t('app', 'Αριθμός Μητρώου'),
            'specialisation_id' => Yii::t('app', 'Ειδικότητα'),
            'school_id' => Yii::t('app', 'School ID'),
            'teacher_gender' => Yii::t('app', 'Φύλο'),
            'teacher_fathername' => Yii::t('app', 'Πατρώνυμο'),
            'teacher_mothername' => Yii::t('app', 'Μητρώνυμο'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDisposalDisposals()
    {
        return $this->hasMany(Disposal::className(), ['teacher_id' => 'teacher_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDisposalLedgers()
    {
        return $this->hasMany(DisposalLedger::className(), ['teacher_id' => 'teacher_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpecialisation()
    {
        return $this->hasOne(Specialisation::className(), ['id' => 'specialisation_id']);
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
     * @return TeacherQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TeacherQuery(get_called_class());
    }
}
