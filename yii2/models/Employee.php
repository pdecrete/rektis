<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;
use admapp\Validators\VatNumberValidator;

/**
 * This is the model class for table "{{%employee}}".
 *
 * @property integer $id
 * @property integer $status
 * @property string $name
 * @property string $surname
 * @property string $fathersname
 * @property string $mothersname
 * @property string $tax_identification_number
 * @property string $email
 * @property string $telephone
 * @property string $mobile
 * @property string $address
 * @property string $identity_number
 * @property string $social_security_number
 * @property integer $specialisation
 * @property string $identification_number
 * @property string $appointment_fek
 * @property string $appointment_date
 * @property integer $service_organic
 * @property integer $service_serve
 * @property integer $position
 * @property string $rank
 * @property string $rank_date
 * @property integer $pay_scale
 * @property string $pay_scale_date
 * @property string $service_adoption
 * @property string $service_adoption_date
 * @property integer $master_degree
 * @property integer $doctorate_degree
 * @property string $work_experience
 * @property string $comments
 * @property integer $deleted
 * @property string $create_ts
 * @property string $update_ts
 *
 * @property EmployeeStatus $status0
 * @property Specialisation $specialisation0
 * @property Service $serviceOrganic
 * @property Service $serviceServe
 * @property Position $position0
 * @property Leave[] $leaves
 */
class Employee extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%employee}}';
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
            [['status', 'specialisation', 'service_organic', 'service_serve', 'position', 'pay_scale', 'master_degree', 'doctorate_degree', 'work_experience', 'deleted'], 'integer'],
            [['name', 'surname', 'fathersname', 'tax_identification_number', 'social_security_number', 'identification_number', 'appointment_fek', 'appointment_date', 'rank', 'pay_scale', 'service_adoption_date'], 'required'],
            [['tax_identification_number'], 'string', 'max' => 9],
            [['tax_identification_number'], VatNumberValidator::className(), 'allowEmpty' => true],
            ['email', 'email'],
            [['appointment_date', 'rank_date', 'pay_scale_date', 'service_adoption_date', 'create_ts', 'update_ts'], 'safe'],
            [['comments'], 'string'],
            [['name', 'surname', 'fathersname', 'mothersname', 'email'], 'string', 'max' => 100],
            [['telephone', 'mobile', 'identity_number', 'social_security_number'], 'string', 'max' => 40],
            [['address'], 'string', 'max' => 200],
            [['identification_number', 'appointment_fek', 'service_adoption'], 'string', 'max' => 10],
            [['rank'], 'string', 'max' => 4],
            [['identification_number'], 'unique'],
            [['identity_number'], 'unique'],
            [['master_degree', 'doctorate_degree', 'work_experience'], 'default', 'value' => 0],
            [['social_security_number'], 'integer'],
            [['social_security_number'], 'string', 'length' => 11],
            [['identification_number'], 'integer'],
            [['identification_number'], 'string', 'length' => 6],
            [['position'], 'exist', 'skipOnError' => true, 'targetClass' => Position::className(), 'targetAttribute' => ['position' => 'id']],
            [['service_organic'], 'exist', 'skipOnError' => true, 'targetClass' => Service::className(), 'targetAttribute' => ['service_organic' => 'id']],
            [['service_serve'], 'exist', 'skipOnError' => true, 'targetClass' => Service::className(), 'targetAttribute' => ['service_serve' => 'id']],
            [['specialisation'], 'exist', 'skipOnError' => true, 'targetClass' => Specialisation::className(), 'targetAttribute' => ['specialisation' => 'id']],
            [['status'], 'exist', 'skipOnError' => true, 'targetClass' => EmployeeStatus::className(), 'targetAttribute' => ['status' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => Yii::t('app', 'Status'),
            'name' => Yii::t('app', 'Name'),
            'surname' => Yii::t('app', 'Surname'),
            'fathersname' => Yii::t('app', 'Father\'s name'),
            'mothersname' => Yii::t('app', 'Mother\'s name'),
            'tax_identification_number' => Yii::t('app', 'TIN'),
            'email' => 'Email',
            'telephone' => Yii::t('app', 'Telephone'),
            'mobile' => Yii::t('app', 'Mobile'),
            'address' => Yii::t('app', 'Address'),
            'identity_number' => Yii::t('app', 'Identity Number'),
            'social_security_number' => Yii::t('app', 'Social Security Number'),
            'specialisation' => Yii::t('app', 'Specialisation'),
            'identification_number' => Yii::t('app', 'Identification Number'),
            'appointment_fek' => Yii::t('app', 'Appointment FEK'),
            'appointment_date' => Yii::t('app', 'Appointment Date'),
            'service_organic' => Yii::t('app', 'Service Organic'),
            'service_serve' => Yii::t('app', 'Service Serve'),
            'position' => Yii::t('app', 'Position'),
            'rank' => Yii::t('app', 'Rank'),
            'rank_date' => Yii::t('app', 'Rank Date'),
            'pay_scale' => Yii::t('app', 'Pay Scale'),
            'pay_scale_date' => Yii::t('app', 'Pay Scale Date'),
            'service_adoption' => Yii::t('app', 'Service Adoption'),
            'service_adoption_date' => Yii::t('app', 'Service Adoption Date'),
            'master_degree' => Yii::t('app', 'No of Master Degrees'),
            'doctorate_degree' => Yii::t('app', 'No of Doctorate Degrees'),
            'work_experience' => Yii::t('app', 'Work Experience'),
            'comments' => Yii::t('app', 'Comments'),
            'create_ts' => 'create ts',
            'update_ts' => 'update ts',
        ];
    }

    public function ranksList()
    {	// associative array ώστε και η τιμή στα select αλλά και η τιμή στη βάση να είναι το αλφαριθμητικό που βλέπω
		// αν αποφασίσουμε να κρατάμε στη βάση κωδικούς 0..5 αντί Α..ΣΤ απλά το ξανακάνω απλό array
		// return ['ΣΤ', 'Ε', 'Δ', 'Γ', 'Β', 'Α'];
        return ['Α'=>'Α', 'Β'=>'Β', 'Γ'=>'Γ', 'Δ' =>'Δ', 'Ε' =>'Ε', 'ΣΤ' => 'ΣΤ'];
    }

    public function payscaleList()
    {
        return [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19];
    }

    public function getFullname()
    {
        return $this->name . ' ' . $this->surname;
    }

    public function getRank0()
    {
        if ($this->rank)
            return $this->ranksList()[$this->rank];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(EmployeeStatus::className(), ['id' => 'status']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpecialisation0()
    {
        return $this->hasOne(Specialisation::className(), ['id' => 'specialisation']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServiceOrganic()
    {
        return $this->hasOne(Service::className(), ['id' => 'service_organic']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServiceServe()
    {
        return $this->hasOne(Service::className(), ['id' => 'service_serve']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosition0()
    {
        return $this->hasOne(Position::className(), ['id' => 'position']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeaves()
    {
        return $this->hasMany(Leave::className(), ['employee' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeavesDuration()
    {
        return $this->hasMany(Leave::className(), ['employee' => 'id'])->where(['deleted' => 0])->sum('duration');
    }

    /**
     * @inheritdoc
     * @return EmployeeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EmployeeQuery(get_called_class());
    }

}
