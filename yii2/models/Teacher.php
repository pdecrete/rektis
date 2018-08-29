<?php

namespace app\models;

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
 *
 * @property DisposalDisposal[] $disposalDisposals
 * @property DisposalLedger[] $disposalLedgers
 * @property Specialisation $specialisation
 * @property Schoolunit $school
 */
class Teacher extends \yii\db\ActiveRecord
{
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
            [['specialisation_id', 'school_id', 'teacher_registrynumber'], 'integer'],
            [['teacher_surname', 'teacher_name'], 'string', 'max' => 100],
            //[['teacher_registrynumber'], 'string', 'max' => 50],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDisposalDisposals()
    {
        return $this->hasMany(DisposalDisposal::className(), ['teacher_id' => 'teacher_id']);
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
