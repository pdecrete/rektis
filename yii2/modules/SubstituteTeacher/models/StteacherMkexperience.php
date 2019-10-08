<?php

namespace app\modules\SubstituteTeacher\models;

use Yii;
use app\modules\SubstituteTeacher\traits\Reference;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use app\modules\SubstituteTeacher\traits\Selectable;
/**
 * This is the model class for table "{{%stteacher_mkexperience}}".
 *
 * @property integer $id
 * @property integer $teacher_id
 * @property date $exp_startdate
 * @property date $exp_enddate
 * @property integer $exp_years
 * @property integer $exp_months
 * @property integer $exp_days
 * @property string $exp_sectorname
 * @property string $exp_sectortype
 * @property string $exp_info
 * @property integer $exp_mkvalid
 *
 * @property Teacher $teacher
 */
class StteacherMkexperience extends \yii\db\ActiveRecord
{
    use Selectable;    
    const EXP_MKINVALID = 0;
    const EXP_MKVALID_DEFAULT = 1;
    const EXP_MKPENDING = 2;
    
    const EXP_PUBLICSECTOR = 0;
    const EXP_NPDD = 1;
    const EXP_NPID = 2;    
    const EXP_PRIVATESECTOR = 3;    

    const EXP_HOURSFULL = 0;
    const EXP_HOURSPART = 1;
    const EXP_HOURSNO = 2;
    
    public $exp_mkvalid_label;
    public $exp_hourslabel;
    public $exp_sectortype_label;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stteacher_mkexperience}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['exp_mkvalid', 'default', 'value' => StteacherMkexperience::EXP_MKVALID_DEFAULT],
            [['exp_years', 'exp_months','exp_days', 'exp_hours'], 'default', 'value' => 0],
            ['exp_years','compare','compareValue'=>'0','operator'=>'>='],
            ['exp_months','compare','compareValue'=>'0','operator'=>'>='],
            ['exp_days','compare','compareValue'=>'0','operator'=>'>='],
            ['exp_mkvalid', 'filter', 'filter' => 'intval'],
            [['exp_startdate', 'exp_enddate', 'teacher_id', 'exp_months', 'exp_days', 'exp_sectorname'], 'required'],
            [['teacher_id', 'exp_years', 'exp_months', 'exp_days', 'exp_mkvalid'], 'integer'],
            //[['exp_startdate', 'exp_enddate'], 'date', 'format' => 'php:Y-m-d'],
            [['exp_hourspweek','exp_startdate', 'exp_enddate'], 'safe'],
            //['exp_enddate','compare','compareAttribute'=>'exp_startdate','operator'=>'>='],
            ['exp_sectorname', 'string', 'max' => 50],
            ['exp_sectortype', 'string', 'max' => 20],
            [['exp_info'], 'string', 'max' => 100],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => Teacher::className(), 'targetAttribute' => ['teacher_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('substituteteacher', 'ID'),
            'teacher_id' => Yii::t('substituteteacher', 'Teacher ID'),
            'exp_startdate' => Yii::t('substituteteacher', 'Exp Startdate'),
            'exp_enddate' => Yii::t('substituteteacher', 'Exp Enddate'),
            'exp_hours' => Yii::t('substituteteacher', 'Exp Hours'),
            'exp_hourspweek' => Yii::t('substituteteacher', 'Exp Hourspweek'),
            'exp_years' => Yii::t('substituteteacher', 'Exp Years'),
            'exp_months' => Yii::t('substituteteacher', 'Exp Months'),
            'exp_days' => Yii::t('substituteteacher', 'Exp Days'),
            'exp_sectorname' => Yii::t('substituteteacher', 'Exp SectorName'),
            'exp_sectortype' => Yii::t('substituteteacher', 'Exp SectorType'),
            'exp_info' => Yii::t('substituteteacher', 'Exp Info'),
            'exp_mkvalid' => Yii::t('substituteteacher', 'Exp Mkvalid'),
        ];
    }

    public static function getExpValidity()
    {
        return [
            (string)self::EXP_MKVALID_DEFAULT => Yii::t('substituteteacher', 'MK VALID'),
            (string)self::EXP_MKINVALID => Yii::t('substituteteacher', 'MK INVALID'),
            (string)self::EXP_MKPENDING => Yii::t('substituteteacher', 'MK PENDING')
        ];
    }    
    public static function getExpHours()
    {
        return [
            (string)self::EXP_HOURSFULL => Yii::t('substituteteacher', 'EXP HOURSFULL'),
            (string)self::EXP_HOURSPART => Yii::t('substituteteacher', 'EXP HOURSPART'),
            (string)self::EXP_HOURSNO => Yii::t('substituteteacher', 'EXP HOURSNO'),            
        ];
    }        
    
    public static function getExpSectorType()
    {
        return [
            (string)self::EXP_PUBLICSECTOR => Yii::t('substituteteacher', 'EXP PUBLIC SECTOR'),
            (string)self::EXP_NPDD => Yii::t('substituteteacher', 'EXP NPDD'),
            (string)self::EXP_NPID => Yii::t('substituteteacher', 'EXP NPID'),
            (string)self::EXP_PRIVATESECTOR => Yii::t('substituteteacher', 'EXP PRIVATE SECTOR')            
        ];
    }        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacher()
    {
        return $this->hasOne(Teacher::className(), ['id' => 'teacher_id']);
    }
    
     public function getTeacherRegistry()
    {
        return $this->hasOne(TeacherRegistry::className(), ['id' => 'registry_id'])
            ->via('teacher');
    }   
    
    public function afterFind()
    {
        parent::afterFind();
        if ($this->exp_hours == self::EXP_HOURSFULL)  { 
            $this->exp_hourslabel = Yii::t('substituteteacher', 'EXP HOURSFULL');
        }  else if ($this->exp_mkvalid == self::EXP_MKINVALID)  {
            $this->exp_hourslabel =Yii::t('substituteteacher', 'EXP HOURSPART');            
        } else {
            $this->exp_hourslabel =Yii::t('substituteteacher', 'EXP HOURSNO');            
        }
        
        if ($this->exp_mkvalid == self::EXP_MKVALID_DEFAULT)  { 
            $this->exp_mkvalid_label = Yii::t('substituteteacher', 'MK VALID');
        }  else if ($this->exp_mkvalid == self::EXP_MKINVALID)  {
            $this->exp_mkvalid_label =Yii::t('substituteteacher', 'MK INVALID');            
        }
        else {
            $this->exp_mkvalid_label =Yii::t('substituteteacher', 'MK PENDING');
        }
    
        if ($this->exp_sectortype == self::EXP_PUBLICSECTOR)  { 
            $this->exp_sectortype_label = Yii::t('substituteteacher', 'EXP PUBLIC SECTOR');
        }  else if ($this->exp_sectortype == self::EXP_NPDD)  {
            $this->exp_sectortype_label =Yii::t('substituteteacher', 'EXP NPDD');            
        }  else if ($this->exp_sectortype == self::EXP_NPID)  {
            $this->exp_sectortype_label =Yii::t('substituteteacher', 'EXP NPID');            
        }  else {
            $this->exp_sectortype_label =Yii::t('substituteteacher', 'EXP PRIVATE SECTOR');
        }
    }
    
    
    
}

