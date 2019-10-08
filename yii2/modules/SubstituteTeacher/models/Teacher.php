<?php

namespace app\modules\SubstituteTeacher\models;

use Yii;
use app\modules\SubstituteTeacher\traits\Selectable;
use app\modules\SubstituteTeacher\traits\Reference;

/**
 * This is the model class for table "{{%stteacher}}".
 *
 * @property integer $id
 * @property integer $registry_id
 * @property integer $year
 * @property integer $public_experience
 * @property integer $smeae_keddy_experience
 * @property integer $disability_percentage
 * @property integer $disabled_children
 * @property integer $three_children
 * @property integer $many_children
 * @property integer $mk_years
 * @property integer $mk_months
 * @property integer $mk_days
 * @property integer $mk_exptotdays
 * @property string  $mk_appdate
 * @property integer $mk_titleyears
 * @property string  $mk_titleappdate
 * @property string  $mk_titleinfo
 * @property integer $mk_yearsper
 * @property integer $mk
 * @property date $mk_changedate
 * @property integer $operation_descr
 * @property integer $sector
 *
 * @property string $name
 *
 * @property PlacementPreference[] $placementPreferences
 * @property StteacherMkexperience[] $stteachermkexperiences
 * @property TeacherRegistry $registry
 * @property TeacherStatusAudit[] $teacherStatusAudits
 * @property Prefecture[] $placementPreferencePrefectures
 * @property TeacherBoard[] $boards
 */
class Teacher extends \yii\db\ActiveRecord
{
    use Selectable;
    use Reference;

    const SCENARIO_CALL_FETCH = 'CALL_FETCH'; // used to specify that model is used in the process of selecting teachers for call

    const TEACHER_STATUS_ELIGIBLE = 0; // can be selected for appointment 
    const TEACHER_STATUS_APPOINTED = 1; // is already appointed 
    const TEACHER_STATUS_NEGATION = 2; // has neglected all appointments 
    const TEACHER_STATUS_PENDING = 3; // is included in an open appointment process 
    const TEACHER_STATUS_DISMISSED = 4; // has been appointed and then dismissed/fired
    const TEACHER_STATUS_CANCELLED = 5; // has been appointed and then cancelled appointment

    const TEACHER_SECTOR_PH = 0; 
    const TEACHER_SECTOR_PL = 1; 
    const TEACHER_SECTOR_PR = 2; 
    const TEACHER_SECTOR_PX = 3; 
    const TEACHER_SECTOR_DH = 4; 
    const TEACHER_SECTOR_DL = 5; 
    const TEACHER_SECTOR_DR = 6; 
    const TEACHER_SECTOR_DX = 7; 
    const TEACHER_SECTOR_KH = 8; 
    const TEACHER_SECTOR_KL = 9; 
    const TEACHER_SECTOR_KR = 10; 
    const TEACHER_SECTOR_KX = 11; 
    
    const TEACHER_TITLEYEARS_NONE = 0; // None    
    const TEACHER_TITLEYEARS_MSC = 4; // MSc
    const TEACHER_TITLEYEARS_PHD = 12; // PhD
    
    const TEACHER_YEARSPER_PETE = 2; // University
    const TEACHER_YEARSPER_DEYE = 3; // Highschool

    
    public $status, $status_label;
    public $name;
    public $titleylabel, $yearsperlabel;
    public $sectorlabel, $operationlabel;
    public $call_use_specialisation_id; // property to hold the specialisation used in a specific call; used in SCENARIO_CALL_FETCH
    public $public_experience_label;
    public $smeae_keddy_experience_label;
    public $mkexp_label;
    public $mktitles_label;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stteacher}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['registry_id', 'year', 'public_experience', 'smeae_keddy_experience', 'disabled_children', 'disability_percentage', 'many_children', 'three_children', 'mk_titleyears', 'mk_yearsper'], 'filter', 'filter' => 'intval'],
            [['disability_percentage', 'disabled_children', 'three_children', 'many_children'], 'default', 'value' => 0],
            [['registry_id', 'year', 'public_experience', 'smeae_keddy_experience', 'disabled_children'], 'integer', 'min' => 0],
            ['disability_percentage', 'integer', 'min' => 0, 'max' => 100],
            [['three_children', 'many_children'], 'integer', 'min' => 0, 'max' => 1],
            [['registry_id', 'year', 'public_experience', 'smeae_keddy_experience', 'disability_percentage', 'disabled_children', 'three_children', 'many_children'], 'required'],
            // this fails after adding with() on main activequery [['year', 'registry_id'], 'unique', 'targetAttribute' => ['year', 'registry_id'], 'message' => 'The combination of Registry ID and Year has already been taken.'],
            ['registry_id', 'validateUniqueInYear'],
            [['registry_id'], 'exist', 'skipOnError' => true, 'targetClass' => TeacherRegistry::className(), 'targetAttribute' => ['registry_id' => 'id']],
            [['call_use_specialisation_id'], 'required', 'on' => self::SCENARIO_CALL_FETCH],
            [['mk_appdate', 'mk_titleappdate', 'mk', 'mk_changedate', 'operation_descr','sector'], 'safe'],
            [['mk_titleinfo'], 'string','max'=>300]
        ];
    }

    public function validateUniqueInYear($attribute, $params, $validator)
    {
        $teachers = Teacher::find()
            ->andWhere([
                'registry_id' => $this->$attribute,
                'year' => $this->year
            ])
            ->andWhere([
                'not', ['id' => $this->id]
            ])
            ->one();

        if (!empty($teachers)) {
            $this->addError($attribute, Yii::t('substituteteacher', 'Teacher is already located in this year.'));
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('substituteteacher', 'ID'),
            'registry_id' => Yii::t('substituteteacher', 'Registry ID'),
            'year' => Yii::t('substituteteacher', 'Year'),
            'status' => Yii::t('substituteteacher', 'Status'),
            'public_experience' => Yii::t('substituteteacher', 'Public experience'),
            'smeae_keddy_experience' => Yii::t('substituteteacher', 'SMEAE/KEDDY experience'),
            'disability_percentage' => Yii::t('substituteteacher', 'Disability percentage'),
            'disabled_children' => Yii::t('substituteteacher', 'Disabled children'),
            'three_children' => Yii::t('substituteteacher', 'Three children'),
            'many_children' => Yii::t('substituteteacher', 'Many children'),
            'mk_years' => Yii::t('substituteteacher', 'Exp Years'),
            'mk_months' => Yii::t('substituteteacher', 'Exp Months'),
            'mk_days' => Yii::t('substituteteacher', 'Exp Days'),
            'mk_exptotdays' => Yii::t('substituteteacher', 'MK Exptotdays'),
            'mk_appdate' => Yii::t('substituteteacher', 'Exp Appdate'),
            'mk_titleyears' => Yii::t('substituteteacher', 'Title Mkyears'),
            'mk_titleappdate' => Yii::t('substituteteacher', 'Title Appdate'),
            'mk_titleinfo' => Yii::t('substituteteacher', 'Title Info'),
            'mk_yearsper' => Yii::t('substituteteacher', 'Years per MK'),
            'mk' => Yii::t('substituteteacher', 'MK'),
            'mk_changedate' => Yii::t('substituteteacher', 'MK Changedate'),
            'operation_descr' => Yii::t('substituteteacher', 'Operation'),
            'sector' => Yii::t('substituteteacher', 'Sector'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlacementPreferences()
    {
        return $this->hasMany(PlacementPreference::className(), ['teacher_id' => 'id'])
            ->orderBy([PlacementPreference::tableName() . '.[[order]]' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlacementPreferencePrefectures()
    {
        return $this->hasMany(Prefecture::className(), ['id' => 'prefecture_id'])
            ->viaTable('{{%stplacement_preference}}', ['prefecture_id' => 'id'])
            ->from(['prefectures' => '{{%stprefecture}}']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistry()
    {
        return $this->hasOne(TeacherRegistry::className(), ['id' => 'registry_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherStatusAudits()
    {
        return $this->hasMany(TeacherStatusAudit::className(), ['teacher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBoards()
    {
        return $this->hasMany(TeacherBoard::className(), ['teacher_id' => 'id']);
    }
    

    
    public function getTeacherMkexperience()
    {
        return $this->hasMany(StteacherMkexperience::className(), ['teacher_id' => 'id'])
                ->orderBy([StteacherMkexperience::tableName() . '.[[exp_startdate]]' => SORT_DESC]);
    }    

    /**
     * Get a list of available choices in the form of
     * ID => LABEL suitable for select lists.
     *
     */
    public static function getChoices($for = 'status')
    {
        $choices = [];
        if ($for === 'status') {
            $choices = [
                self::TEACHER_STATUS_ELIGIBLE => Yii::t('substituteteacher', 'Eligible for appointment'),
                self::TEACHER_STATUS_APPOINTED => Yii::t('substituteteacher', 'Teacher appointed'),
                self::TEACHER_STATUS_NEGATION => Yii::t('substituteteacher', 'Teacher denied appointment'),
                self::TEACHER_STATUS_PENDING => Yii::t('substituteteacher', 'Teacher status pending'),
                self::TEACHER_STATUS_DISMISSED => Yii::t('substituteteacher', 'Teacher dismissed'),
                self::TEACHER_STATUS_CANCELLED => Yii::t('substituteteacher', 'Teacher appointment cancelled'),
            ];
        } elseif ($for === 'year') {
            // one year before and 2 ahead...
            $year = (int)date('Y');
            $years = range($year - 1, $year + 2);
            $choices = array_combine($years, $years);
        }

        return $choices;
    }

    public static function statusLabel($status) {
        switch ($status) {
            case self::TEACHER_STATUS_ELIGIBLE:
                $status_label = Yii::t('substituteteacher', 'Eligible for appointment');
                break;
            case self::TEACHER_STATUS_APPOINTED:
                $status_label = Yii::t('substituteteacher', 'Teacher appointed');
                break;
            case self::TEACHER_STATUS_NEGATION:
                $status_label = Yii::t('substituteteacher', 'Teacher denied appointment');
                break;
            case self::TEACHER_STATUS_PENDING:
                $status_label = Yii::t('substituteteacher', 'Teacher status pending');
                break;
            case self::TEACHER_STATUS_DISMISSED:
                $status_label = Yii::t('substituteteacher', 'Teacher dismissed');
                break;
            case self::TEACHER_STATUS_CANCELLED:
                $status_label = Yii::t('substituteteacher', 'Teacher appointment cancelled');
                break;
            default:
                $status_label = null;
                break;
        }
        return $status_label;
    }
    
    public static function getSectors()
    {
        $sectors = [];
        $sectors = [
                self::TEACHER_SECTOR_PH => Yii::t('substituteteacher', 'Διεύθυνση Πρωτοβάθμιας Ηρακλείου'),
                self::TEACHER_SECTOR_PL => Yii::t('substituteteacher', 'Διεύθυνση Πρωτοβάθμιας Λασιθίου'),            
                self::TEACHER_SECTOR_PR => Yii::t('substituteteacher', 'Διεύθυνση Πρωτοβάθμιας Ρεθύμνου'),            
                self::TEACHER_SECTOR_PX => Yii::t('substituteteacher', 'Διεύθυνση Πρωτοβάθμιας Χανίων'),
                self::TEACHER_SECTOR_DH => Yii::t('substituteteacher', 'Διεύθυνση Δευτεροβάθμιας Ηρακλείου'),
                self::TEACHER_SECTOR_DL => Yii::t('substituteteacher', 'Διεύθυνση Δευτεροβάθμιας Λασιθίου'),
                self::TEACHER_SECTOR_DR => Yii::t('substituteteacher', 'Διεύθυνση Δευτεροβάθμιας Ρεθύμνου'),
                self::TEACHER_SECTOR_DX => Yii::t('substituteteacher', 'Διεύθυνση Δευτεροβάθμιας Χανίων'),
                self::TEACHER_SECTOR_KH => Yii::t('substituteteacher', 'ΚΕΣΥ Ηρακλείου'),
                self::TEACHER_SECTOR_KL => Yii::t('substituteteacher', 'ΚΕΣΥ Λασιθίου'),
                self::TEACHER_SECTOR_KR => Yii::t('substituteteacher', 'ΚΕΣΥ Ρεθύμνου'),
                self::TEACHER_SECTOR_KX => Yii::t('substituteteacher', 'ΚΕΣΥ Χανίων')
            ];
        return $sectors;
    }    

    public static function getMkTitleYears()
    {
        return [
            (string)self::TEACHER_TITLEYEARS_NONE => Yii::t('substituteteacher', 'Title None'),            
            (string)self::TEACHER_TITLEYEARS_MSC => Yii::t('substituteteacher', 'Title MSc'),
            (string)self::TEACHER_TITLEYEARS_PHD => Yii::t('substituteteacher', 'Title PhD')
        ];
    }        

    public static function getMkCateg()
    {
        return [
            (string)self::TEACHER_YEARSPER_PETE => Yii::t('substituteteacher', 'Education University'),            
            (string)self::TEACHER_YEARSPER_DEYE => Yii::t('substituteteacher', 'Education Highschool'),
        ];
    }        

    public static function getYearChoices()
    {
        $options = [];
        $year = date('Y');
        for ($y = 2018; $y <= $year; $y++) {
            $options["$y"] = $y;
        }
        return $options;
    }    
    /*
    public static function calcMK($model)
    {
            //echo "<pre>";print_r($model); echo "</pre>"; die();
            if (!empty($model->mk_titleappdate) && !empty($model->mk_appdate)) {
                $model->mk = 1 + intval(($model->mk_titleyears + $model->mk_years)/$model->mk_yearsper);
            } else if (!empty($model->mk_titleappdate)) {
                $model->mk = 1 + intval($model->mk_titleyears/$model->mk_yearsper);    
            } else if (!empty($model->mk_appdate)) {
                $model->mk = 1 + intval($model->mk_years/$model->mk_yearsper);    
            } else {
                $model->mk = 1;
            }   
            //echo "<pre>";print_r($model); echo "</pre>"; die();
    }       
    */
    /**
     * @see TeacherStatusAudit::audit 
     */
    public function audit($audit_message, $audit_relevant_data = [])
    {
        return TeacherStatusAudit::audit($this->id, empty($this->status) ? self::TEACHER_STATUS_PENDING : $this->status, $audit_message, $audit_relevant_data);
    }

    public static function defaultSelectables($index_property = 'id', $label_property = 'name', $group_property = null)
    {
        return static::selectables($index_property, $label_property, $group_property, null);
    }

    /**
     * The status of the teacher is calculcated with the following logic:
     * - If she/he has been appointed in any of the boards, set to appointed 
     * - If she/he is currenlty involved in an appointment process, set to pending 
     * - If she/he has declined from a board, it does not affect eligibility, unless this was the only board
     * - If she/he has been dismissed from a board, it does not affect eligibility, unless this was the only board
     */


    public function afterFind()
    {
        parent::afterFind();

        $this->name = " ({$this->year}) " . ($this->registry ? $this->registry->name : '-')  ;
        // get the combined status 
        $this->status = self::TEACHER_STATUS_ELIGIBLE; 
        
        if ($this->mk_titleyears == self::TEACHER_TITLEYEARS_NONE) {
            $this->titleylabel = Yii::t('substituteteacher', 'Title None');
        } else if ($this->mk_titleyears == self::TEACHER_TITLEYEARS_MSC)  {
            $this->titleylabel =Yii::t('substituteteacher', 'Title MSc');            
        } else if ($this->mk_titleyears == self::TEACHER_TITLEYEARS_PHD){
            $this->titleylabel =Yii::t('substituteteacher', 'Title PhD');
        }

        if ($this->mk_yearsper == self::TEACHER_YEARSPER_PETE) {
            $this->yearsperlabel = Yii::t('substituteteacher', 'Education University');
        } else if ($this->mk_yearsper == self::TEACHER_YEARSPER_DEYE)  {
            $this->yearsperlabel =Yii::t('substituteteacher', 'Education Highschool');            
        }
        
        if ($this->sector == self::TEACHER_SECTOR_DH) {
            $this->sectorlabel =  'Διεύθυνση Δευτεροβάθμιας Ηρακλείου';
        } else if ($this->sector == self::TEACHER_SECTOR_DL)  {
            $this->sectorlabel =  'Διεύθυνση Δευτεροβάθμιας Λασιθίου';         
        } else if ($this->sector == self::TEACHER_SECTOR_DR)  {
            $this->sectorlabel =  'Διεύθυνση Δευτεροβάθμιας Ρεθύμνου';           
        } else if ($this->sector == self::TEACHER_SECTOR_DX)  {
            $this->sectorlabel =  'Διεύθυνση Δευτεροβάθμιας Χανίων';         
        } else if ($this->sector == self::TEACHER_SECTOR_PH)  {
            $this->sectorlabel =  'Διεύθυνση Πρωτοβάθμιας Ηρακλείου';         
        } else if ($this->sector == self::TEACHER_SECTOR_PL)  {
            $this->sectorlabel =  'Διεύθυνση Πρωτοβάθμιας Λασιθίου';          
        } else if ($this->sector == self::TEACHER_SECTOR_PR)  {
            $this->sectorlabel =  'Διεύθυνση Πρωτοβάθμιας Ρεθύμνου';            
        } else if ($this->sector == self::TEACHER_SECTOR_PX)  {
            $this->sectorlabel =  'Διεύθυνση Πρωτοβάθμιας Χανίων';           
        } else if ($this->sector == self::TEACHER_SECTOR_KH)  {
            $this->sectorlabel =  'ΚΕΣΥ ΗΡΑΚΛΕΙΟΥ';            
        } else if ($this->sector == self::TEACHER_SECTOR_KL)  {
            $this->sectorlabel =  'ΚΕΣΥ ΛΑΣΙΘΙΟΥ';;            
        } else if ($this->sector == self::TEACHER_SECTOR_KR)  {
            $this->sectorlabel =  'ΚΕΣΥ ΡΕΘΥΜΝΟΥ';            
        } else if ($this->sector == self::TEACHER_SECTOR_KX)  {
            $this->sectorlabel =  'ΚΕΣΥ ΧΑΝΙΩΝ';   
        } else {
            $this->sectorlabel =  'Μη ορισμένο';   
        }       
        
        $boards = $this->boards;
        if (empty($boards)) {
            $this->status = self::TEACHER_STATUS_ELIGIBLE; 
        } elseif (count($boards) == 1) {
            $sole_board = reset($boards);
            $this->status = $sole_board->status;
        } else {
            $statuses = array_map(function ($bm) {
                return $bm->status;
            }, $boards);
            if (in_array(self::TEACHER_STATUS_APPOINTED, $statuses)) {
                $this->status = self::TEACHER_STATUS_APPOINTED;
            } elseif (in_array(self::TEACHER_STATUS_PENDING, $statuses)) {
                $this->status = self::TEACHER_STATUS_PENDING;
            } elseif (in_array(self::TEACHER_STATUS_ELIGIBLE, $statuses)) {
                $this->status = self::TEACHER_STATUS_ELIGIBLE;
            } elseif (in_array(self::TEACHER_STATUS_DISMISSED, $statuses)) {
                $this->status = self::TEACHER_STATUS_DISMISSED;
            } elseif (in_array(self::TEACHER_STATUS_CANCELLED, $statuses)) {
                $this->status = self::TEACHER_STATUS_CANCELLED;
            } else {
                $this->status = self::TEACHER_STATUS_NEGATION;
            }
        }
        $this->status_label = self::statusLabel($this->status);

        foreach (['public_experience', 'smeae_keddy_experience'] as $field) {
            $days = intval($this->$field % 30);
            $months_rem = intval(($this->$field - $days) / 30);
            $months = $months_rem % 12;
            $years = intval($months_rem / 12);
            $label_field = "{$field}_label";
            $this->$label_field = Yii::t('substituteteacher', '{y,plural,=0{} =1{# year, } other{# years, }}{m,plural,=0{} =1{# month, } other{# months, }}{d,plural,=0{} =1{# day} other{# days}}', ['d' => $days, 'm' => $months, 'y' => $years]);
        }
        $this->mkexp_label = Yii::t('substituteteacher', '{y,plural,=0{} =1{# year, } other{# years, }}{m,plural,=0{} =1{# month, } other{# months, }}{d,plural,=0{} =1{# day} other{# days}}', ['d' => $this->mk_days, 'm' => $this->mk_months, 'y' => $this->mk_years]); 
    }
 
    /**
     * Define fields that should be returned when the model is exposed
     * by or for an API call.
     */
    public function toApi()
    {
        // If the model has set the call_use_specialisation_id property, use that one as
        // the specialisation. Otherwise return all specialisations with it.
        $specialty = $specialty_id = null;
        $specialisations = $this->registry->specialisations;
        if ($this->scenario === Teacher::SCENARIO_CALL_FETCH) {
            foreach ($specialisations as $specialisation) {
                if ($specialisation->id == $this->call_use_specialisation_id) {
                    $specialty = $specialisation->code;
                    $specialty_id = $specialisation->id;
                }
            }
        } else {
            $specialty = '-'; // multiple; don't serve frontend...
            $specialty_id = array_map(function ($m) {
                return $m->id;
            }, $specialisations);
        }

        return array_merge(
            [
                'specialty' => $specialty,
                'vat' => $this->registry->tax_identification_number,
                'identity' => $this->registry->identity_number,
                'ref' => $this->buildReference([
                    'id' => $this->id,
                    'specialty_id' => $specialty_id,
                    'firstname' => $this->registry->firstname,
                    'lastname' => $this->registry->surname,
                    'fathername' => $this->registry->fathername,
                    'mothername' => $this->registry->mothername,
                    'email' => $this->registry->email,
                    'mobile_phone' => $this->registry->mobile_phone,
                ])
            ],
            (YII_DEBUG ? [ // only for debugging
                // 'name' => $this->registry->name,
                'specialty_id' => $specialty_id,
                'firstname' => $this->registry->firstname,
                'lastname' => $this->registry->surname,
                'fathername' => $this->registry->fathername,
                'mothername' => $this->registry->mothername,
                'email' => $this->registry->email,
                'mobile_phone' => $this->registry->mobile_phone,
            ] : [
            ])
        );
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
