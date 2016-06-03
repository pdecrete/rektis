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
            [['name', 'surname', 'fathersname', 'tax_identification_number', 'social_security_number', 'identification_number', 'appointment_fek', 'appointment_date', 'rank', 'pay_scale', 'service_adoption_date'], 'required'],
            [['tax_identification_number'], 'string', 'max' => 9],
            [['tax_identification_number'], VatNumberValidator::className(), 'allowEmpty' => true],
            ['email', 'email'],
            [['appointment_date', 'rank_date', 'pay_scale_date', 'service_adoption_date', 'create_ts', 'update_ts'], 'safe'],
            [['comments'], 'string'],
            [['name', 'surname', 'fathersname', 'mothersname', 'email'], 'string', 'max' => 100],
            [['telephone', 'identity_number', 'social_security_number'], 'string', 'max' => 40],
            [['address'], 'string', 'max' => 200],
            [['identification_number', 'appointment_fek', 'service_adoption'], 'string', 'max' => 10],
            [['rank'], 'string', 'max' => 4],
            [['identification_number'], 'unique'],
            [['identity_number'], 'unique'],
            [['master_degree','doctorate_degree','work_experience'], 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Εργασιακή κατάσταση',
            'name' => 'Όνομα',
            'surname' => 'Επώνυμο',
            'fathersname' => 'Όνομα πατέρα',
            'mothersname' => 'Όνομα μητέρας',
            'tax_identification_number' => 'Α.Φ.Μ.',
            'email' => 'Email',
            'telephone' => 'Τηλέφωνο',
            'address' => 'Διεύθυνση',
            'identity_number' => 'Α.Δ.Ταυτότητας',
            'social_security_number' => 'Α.Μ.Κ.Α.',
            'specialisation' => 'Ειδικότητα',
            'identification_number' => 'Αριθμός μητρώου',
            'appointment_fek' => 'ΦΕΚ διορισμού',
            'appointment_date' => 'Ημερομηνία διορισμού',
            'service_organic' => 'Οργανικη θέση',
            'service_serve' => 'Θέση όπου υπηρετεί',
            'position' => 'Θεση (ευθύνης κλπ)',
            'rank' => 'Βαθμός',
            'rank_date' => 'Ημερομηνία απόκτησης βαθμού',
            'pay_scale' => 'Μισθολογικό κλιμάκιο',
            'pay_scale_date' => 'Ημερομηνία απόκτησης κλιμακίου',
            'service_adoption' => 'Ανάληψη υπηρεσίας',
            'service_adoption_date' => 'Ημερομηνία ανάληψης υπηρεσίας',
            'master_degree' => 'Πλήθος μεταπτυχιακών τίτλων',
            'doctorate_degree' => 'Πλήθος διδακτορικών τίτλων',
            'work_experience' => 'Προϋπηρεσία σε ημέρες',
            'comments' => 'Σχόλια',
            'create_ts' => 'create ts',
            'update_ts' => 'update ts',
        ];
    }

    public function ranksList()
    {
        return ['ΣΤ', 'Ε', 'Δ', 'Γ', 'Β', 'Α'];
    }

    public function payscaleList()
    {
        return [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19];
    }

    public function getRank0(){
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
     * @inheritdoc
     * @return EmployeeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EmployeeQuery(get_called_class());
    }

}
