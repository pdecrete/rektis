<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;

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
 * @property string $create_ts
 * @property string $update_ts
 *
 * @property EmployeeStatus $status0
 * @property Specialisation $specialisation0
 * @property Service $serviceOrganic
 * @property Service $serviceServe
 * @property Position $position0
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
            [['status', 'specialisation', 'service_organic', 'service_serve', 'position', 'pay_scale', 'master_degree', 'doctorate_degree', 'work_experience'], 'integer'],
            [['name', 'surname', 'fathersname', 'mothersname', 'tax_identification_number', 'email', 'telephone', 'address', 'identity_number', 'social_security_number', 'identification_number', 'appointment_fek', 'appointment_date', 'rank', 'rank_date', 'pay_scale', 'pay_scale_date', 'service_adoption', 'service_adoption_date', 'work_experience', 'comments'], 'required'],
            [['appointment_date', 'rank_date', 'pay_scale_date', 'service_adoption_date', 'create_ts', 'update_ts'], 'safe'],
            [['comments'], 'string'],
            [['name', 'surname', 'fathersname', 'mothersname', 'email'], 'string', 'max' => 100],
            [['tax_identification_number'], 'string', 'max' => 9],
            [['telephone', 'identity_number', 'social_security_number'], 'string', 'max' => 40],
            [['address'], 'string', 'max' => 200],
            [['identification_number', 'appointment_fek', 'service_adoption'], 'string', 'max' => 10],
            [['rank'], 'string', 'max' => 4],
            [['identification_number'], 'unique'],
            [['identity_number'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'εργασιακη κατασταση',
            'name' => 'Name',
            'surname' => 'Surname',
            'fathersname' => 'Fathersname',
            'mothersname' => 'Mothersname',
            'tax_identification_number' => 'ΑΦΜ',
            'email' => 'Email',
            'telephone' => 'Telephone',
            'address' => 'Address',
            'identity_number' => 'ταυτοτητα',
            'social_security_number' => 'ΑΜΚΑ',
            'specialisation' => 'Ειδικοτητα',
            'identification_number' => 'αριθμος μητρωου',
            'appointment_fek' => 'ΦΕΚ διορισμου',
            'appointment_date' => 'ημερομηνια διορισμου',
            'service_organic' => 'οργανικη θεση',
            'service_serve' => 'θεση οπου υπηρετει',
            'position' => 'θεση (ευθύνης κλπ)',
            'rank' => 'βαθμος',
            'rank_date' => 'Rank Date',
            'pay_scale' => 'μισθολογικο κλιμακιο',
            'pay_scale_date' => 'Pay Scale Date',
            'service_adoption' => 'αναληψη υπηρεσιας',
            'service_adoption_date' => 'Service Adoption Date',
            'master_degree' => 'πληθος μεταπτυχιακων τιτλων',
            'doctorate_degree' => 'πληθος διδακτορικων τιτλων',
            'work_experience' => 'προυπηρεσια σε ημερες',
            'comments' => 'Comments',
            'create_ts' => 'Create Ts',
            'update_ts' => 'Update Ts',
        ];
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
     * @inheritdoc
     * @return EmployeeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EmployeeQuery(get_called_class());
    }

}
