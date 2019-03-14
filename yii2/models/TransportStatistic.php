<?php
namespace app\models;

use DateTime;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use app\modules\eduinventory\components\EduinventoryHelper;

class TransportStatistic extends Model
{
    const GROUPBY_YEAR = 'byYear';
    const GROUPBY_TRANSPORTVEHICLE = 'byTransportvehicle';
    const GROUPBY_SPECIALISATION = 'bySpecialisation';
    const GROUPBY_POSITIONUNIT = 'byPositionunit';
    const GROUPBY_EMPLOYEEOPTION = 'byEmployee';
    const GROUPBY_EXPENDITURETYPE = 'byExpendituretype';
    const GROUPBY_TRANSPORTDAYS = 'byTransportdays';
    const GROUPBY_TRANSPORTDAYSOUT = 'byTransportawaydays';
    const GROUPBY_TRANSPORTNIGHTSOUT= 'byTransportovernights';

    const CHARTTYPE_BAR = 'bar';
    const CHARTTYPE_HORIZONTALBAR = 'horizontalBar';
    const CHARTTYPE_PIE = 'pie';
    const CHARTTYPE_DOUGHNUT = 'doughnut';
    const CHARTTYPE_POLARAREA = 'polarArea';

    public $statistic_year;
    public $statistic_vehicle;
    public $statistic_specialisation;
    public $statistic_positionunit;
    public $statistic_employee;
    public $statistic_expendituretype;
    public $statistic_days;
    public $statistic_daysout;
    public $statistic_nightsout;
    public $statistic_groupby;
    public $statistic_charttype;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [[['statistic_year', 'statistic_vehicle', 'statistic_specialisation', 'statistic_positionunit',
                  'statistic_employee', 'statistic_expendituretype', 'statistic_days', 'statistic_daysout', 'statistic_nightsout'], 'required'],
                [['statistic_year'], 'each', 'rule' => ['integer']],
                [['statistic_vehicle'], 'string'],
                [['statistic_specialisation'], 'string'],
                [['statistic_positionunit'], 'string'],
                [['statistic_employee'], 'string'],
                [['statistic_expendituretype'], 'string'],
                [['statistic_days'], 'string'],
                [['statistic_daysout'], 'string'],
                [['statistic_nightsout'], 'string'],
                [['statistic_groupby'], 'string'],
                [['statistic_charttype'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */

    public function attributeLabels()
    {
        return ['statistic_year' => Yii::t('app', 'Year'),
                'statistic_vehicle' => Yii::t('app', 'Transport Mode'),
                'statistic_specialisation' => Yii::t('app', 'Specialisation'),
                'statistic_positionunit' => Yii::t('app', 'Service'),
                'statistic_employee' => Yii::t('app', 'Employee'),
                'statistic_expendituretype' => Yii::t('app', 'Expenditure type'),
                'statistic_days' => Yii::t('app', 'Duration'),
                'statistic_daysout' => Yii::t('app', 'Duration'),
                'statistic_nightsout' => Yii::t('app', 'Nigths out'),
                'statistic_employee' => Yii::t('app', 'Employee'),
                'statistic_charttype' => Yii::t('app', 'Chart Type'),
                'statistic_groupby' => Yii::t('app', 'Group by'),
               ];
    }

    /**
     * Returns the description of the statistic as it has benn defined through the instance variables.
     *
     * @return string
     */
    public function getStatisticDescription()
    {
        $years = $this->statistic_year;
        $years_count = count($years);
        $standard_phrase = 'Πλήθος μετακινήσεων';
        if ($years_count == 1) {
            $years_part = ' κατά το έτος "';
        } else {
            $years_part = ' κατά τα έτη "';
        }

        $vehicle_part = '';
        $duration_part = '';
        $days_part = '';
        $daysout_part = '';
        $nightsout_part = '';
        $expendituretype_part = '';
        $teachers_part = '';
        $specific_teacher_part = '';
        $specialisation_part = '';        
        $positionunit_part = '';
        $putcomma = false;
        $counter = 0;
        foreach ($years as $year) {
            $counter++;
            $years_part .= $year;
            if ($counter < $years_count) {
                $years_part .=  ', ';
            } else {
                $years_part .= '"';
            }
        }

        if ($this->statistic_expendituretype != 'ALL') {
            $expendituretype_part = ' με τύπο δαπάνης "' . TransportType::findOne([$this->statistic_expendituretype])['name'] . '"';
            $putcomma = true;
        }
        
        if ($this->statistic_vehicle != 'ALL') {
            if($putcomma)
                $vehicle_part = ',';
            $vehicle_part .= ' με μέσο μετακίνησης "' . TransportMode::findOne(['id' => $this->statistic_vehicle])['name'] . '"';
            $putcomma = true;
        }
        
        if($this->statistic_days != 'ALL' || $this->statistic_daysout != 'ALL' || $this->statistic_nightsout != 'ALL') {
            if($putcomma) 
                $duration_part = ',';
            $duration_part .= ' διάρκειας ';
            $putcomma = false;
        }
            
        if ($this->statistic_days != 'ALL') {
            $days_part .= ' '  .$this->statistic_days . ' ημερών (συνολικά)';
            $putcomma = true;
        }
        
        if ($this->statistic_daysout != 'ALL') {
            if($putcomma)
                $daysout_part = ',';
            $daysout_part .= ' '  .$this->statistic_daysout . ' ημερών εκτός έδρας';
            $putcomma = true;
        }
        
        if ($this->statistic_nightsout != 'ALL') {
            if($putcomma)
                $nightsout_part = ',';
            $nightsout_part .= ' '  .$this->statistic_nightsout . ' διανυκτερεύσεων';
            $putcomma = true;
        }

        if($this->statistic_employee != 'ALL') {            
            if($putcomma)
                $specific_teacher_part = ',';
            $stat_employee = Employee::findOne(['id' => $this->statistic_employee]);
            $specific_teacher_part .= ' για τον εργαζόμενο ' . $stat_employee['surname'] . ' ' . $stat_employee['name'];
            $putcomma = true;
        }
        
        if (($this->statistic_specialisation != 'ALL' || $this->statistic_positionunit != 'ALL') && $this->statistic_employee == 'ALL') {            
            if($putcomma)
                $teachers_part = ',';
            $teachers_part .= " εργαζομένων";
            $putcomma = false;
        }
        
        if ($this->statistic_specialisation != 'ALL' && $this->statistic_employee == 'ALL') {
            $specialisation_part .= ' ειδικότητας "' . Specialisation::findOne(['id', $this->statistic_specialisation])['code'] . '"';
            $putcomma = true;
        }

        if ($this->statistic_positionunit != 'ALL' && $this->statistic_employee == 'ALL') {
            if($putcomma)
                $positionunit_part .= ',';
            $positionunit_part .= ' υπηρετούντες στην υπηρεσία "' . Service::findOne(['id' => $this->statistic_positionunit])['name'] . '"';
        }

        $standard_phrase .= $years_part . $expendituretype_part . $vehicle_part . $duration_part . $days_part . $daysout_part . $nightsout_part . $specific_teacher_part . $teachers_part . $specialisation_part . $positionunit_part . ' ' .
                    '(ομαδοποίηση ανά ' . mb_strtolower(self::getGroupByOptions()[$this->statistic_groupby]) . ')';

        return $standard_phrase;
    }

    /**
     * Returns the statistics based on the values of the instance variables.
     *
     * @return array
     */
    public function getStatistics()
    {     
        $tblprefix = Yii::$app->db->tablePrefix;
        $t = $tblprefix . 'transport';
        $tm = $tblprefix . 'transport_mode';
        $texpt = $tblprefix . 'transport_type';
        $pu = $tblprefix . 'service';
        $specs = $tblprefix . 'specialisation';
        $e = $tblprefix . 'employee';

        $groupby_options = TransportStatistic::getGroupByOptions();
        $andWhereCondition = '';
        $data = [];
        $index = 0;

        if ($this->statistic_groupby == TransportStatistic::GROUPBY_YEAR) {
            $years = $this->statistic_year;
            foreach ($years as $year) {
                $andWhereCondition = $t . ".start_date >= '" . $year . "-01-01' AND " .
                                     $t . ".start_date <= '" . $year . "-12-31'";
                $data['LABELS'][$index] = $year;
                $data['TRANSPORTS_COUNT'][$index] = TransportStatistic::countTransports($andWhereCondition);
                $index++;
            }
        } elseif ($this->statistic_groupby == TransportStatistic::GROUPBY_TRANSPORTVEHICLE) {
            $transportvehicles = TransportStatistic::getTransportVehicleOptions();
            foreach ($transportvehicles as $vehicleid=>$transportvehicle) {
                $andWhereCondition = $t . ".mode=" . $vehicleid;
                $tranports_count = TransportStatistic::countTransports($andWhereCondition);
                if ($tranports_count != 0) {
                    $data['LABELS'][$index] = $transportvehicle;
                    $data['TRANSPORTS_COUNT'][$index] = $tranports_count;
                    $index++;
                }
            }
        } elseif ($this->statistic_groupby == TransportStatistic::GROUPBY_SPECIALISATION) {
            $specialisations = EduinventoryHelper::getSpecializations();            
            foreach ($specialisations as $specialisation_id => $specialisation_code) {
                $andWhereCondition = $specs . ".id=" . $specialisation_id;
                $tranports_count = TransportStatistic::countTransports($andWhereCondition);
                if ($tranports_count != 0) {
                    $data['LABELS'][$index] = $specialisation_code;
                    $data['TRANSPORTS_COUNT'][$index] = $tranports_count;
                    $index++;
                }
            }
        } elseif($this->statistic_groupby == TransportStatistic::GROUPBY_POSITIONUNIT){
            $positionunits = TransportStatistic::getPositionUnitsOptions();
            foreach ($positionunits as $id=>$positionunit) {
                $andWhereCondition = $e . ".service_serve=" . $id;
                $tranports_count = TransportStatistic::countTransports($andWhereCondition);
                if($tranports_count != 0) {
                    $data['LABELS'][$index] = $positionunit;
                    $data['TRANSPORTS_COUNT'][$index] = $tranports_count;
                    $index++;
                }
            }
        } elseif($this->statistic_groupby == TransportStatistic::GROUPBY_EXPENDITURETYPE){ 
            $expend_types = TransportStatistic::getTransportExpenditureTypeOptions();
            foreach ($expend_types as $id=>$expend_type) {
                $andWhereCondition = $t . ".type=" . $id;
                $tranports_count = TransportStatistic::countTransports($andWhereCondition);
                if($tranports_count != 0) {
                    $data['LABELS'][$index] = $expend_type;
                    $data['TRANSPORTS_COUNT'][$index] = $tranports_count;
                    $index++;
                }
            }
        } elseif($this->statistic_groupby == TransportStatistic::GROUPBY_TRANSPORTDAYS){
            $days = TransportStatistic::getDaysOptions();
            foreach ($days as $day) {
                $andWhereCondition = $t . ".days_applied=" . $day;
                $tranports_count = TransportStatistic::countTransports($andWhereCondition);
                if($tranports_count != 0) {
                    $data['LABELS'][$index] = $day;
                    $data['TRANSPORTS_COUNT'][$index] = $tranports_count;
                    $index++;
                }
            }
        } elseif($this->statistic_groupby == TransportStatistic::GROUPBY_TRANSPORTDAYSOUT){
            $days = TransportStatistic::getDaysOutOptions();
            foreach ($days as $day) {
                $andWhereCondition = $t . ".days_out=" . $day;
                $tranports_count = TransportStatistic::countTransports($andWhereCondition);
                if($tranports_count != 0) {
                    $data['LABELS'][$index] = $day;
                    $data['TRANSPORTS_COUNT'][$index] = $tranports_count;
                    $index++;
                }
            }
        } elseif($this->statistic_groupby == TransportStatistic::GROUPBY_TRANSPORTNIGHTSOUT){
            $nights = TransportStatistic::getNightsOutOptions();
            foreach ($nights as $night) {
                $andWhereCondition = $t . ".nights_out=" . $night;
                $tranports_count = TransportStatistic::countTransports($andWhereCondition);
                if($tranports_count != 0) {
                    $data['LABELS'][$index] = $night;
                    $data['TRANSPORTS_COUNT'][$index] = $tranports_count;
                    $index++;
                }
            }
        } else {
            $employees = TransportStatistic::getEmployeeOptions();
            foreach ($employees as $id=>$employee_surname) {
                $andWhereCondition = $e . ".id = " . $id;
                $transports_count = TransportStatistic::countTransports($andWhereCondition);
                if ($transports_count != 0) {
                    $data['LABELS'][$index] = $employee_surname;
                    $data['TRANSPORTS_COUNT'][$index] = $transports_count;
                    $index++;
                }
            }
        }
        
        return $data;
    }

    private function reduceLength($string, $newlength = 25) {
        return mb_substr($string, 0, $newlength) . "..";
    }

    /**
     * Returns the number of transports based on the condition passed as parameter.
     *
     * @param string $andWhereCondition
     */
    protected function countTransports($andWhereCondition)
    {
        $tblprefix = Yii::$app->db->tablePrefix;
        $t = $tblprefix . 'transport';
        $tm = $tblprefix . 'transport_mode';
        $texpt = $tblprefix . 'transport_type';
        $pu = $tblprefix . 'service';
        $specs = $tblprefix . 'specialisation';
        $e = $tblprefix . 'employee';
        $es = $tblprefix . 'employee_status';
        
        $query = (new \yii\db\Query())
        ->select("COUNT(" . $t . ".id) AS TRANSPORTS_COUNT")
        ->from($t . "," . $tm . "," . $texpt . "," . $specs . "," .  $e . "," . $pu . "," . $es) //. $pt . "," 
        ->where($t . ".deleted=0")
        ->andWhere($t . ".mode=" . $tm . ".id")
        ->andWhere($t . ".type=" . $texpt . ".id")
        ->andWhere($t . ".employee=" . $e. ".id")
        ->andWhere($e . ".specialisation=" . $specs . ".id")
        ->andWhere($e . ".status=" . $es . ".id")
        ->andWhere($e . ".service_serve=" . $pu . ".id");

        
        if ($this->statistic_vehicle != 'ALL') {
            $query = $query->andWhere($t . ".mode=" . $this->statistic_vehicle);
        }
        
        if ($this->statistic_specialisation != 'ALL') {
            $query = $query->andWhere($e . ".specialisation=" . $this->statistic_specialisation);
        }        
        
        if ($this->statistic_positionunit != 'ALL') {
            $query = $query->andWhere($pu . ".id=" . $this->statistic_positionunit);
        }       
        
        if ($this->statistic_employee != 'ALL') {
            $query = $query->andWhere($e . ".id=" . $this->statistic_employee);
        }
        
        if ($this->statistic_expendituretype != 'ALL') {            
            $query = $query->andWhere($texpt . ".id=" . $this->statistic_expendituretype);
        }
        
        if ($this->statistic_days != 'ALL') {
            $query = $query->andWhere($t . ".days_applied=" . $this->statistic_days);
        }
        
        if ($this->statistic_daysout != 'ALL') {
            $query = $query->andWhere($t . ".days_out=" . $this->statistic_daysout);
        }

        if ($this->statistic_nightsout != 'ALL') {
            $query = $query->andWhere($t . ".nights_out=" . $this->statistic_nightsout);
        }
        
        $firstyear_flag = false;
        foreach ($this->statistic_year as $year) {
            if (!$firstyear_flag) {
                $subquery = "(" . $t . ".start_date >= '" . $year . "-01-01' AND " . $t . ".start_date <= '" . $year . "-12-31')";
                $firstyear_flag = true;
            } else {
                $subquery .= " OR " . "(" . $t . ".start_date >= '" . $year . "-01-01' AND " .
                    $t . ".start_date <= '" . $year . "-12-31')";
            }
        }
        $query = $query->andWhere($subquery);
        $query = $query->andWhere($andWhereCondition);//echo $query->createCommand()->rawSql; die();
        return $query->one()['TRANSPORTS_COUNT'];
    }
    
    /**
     * Returns the year options based on the dates of the transports that exist in the database.
     *
     * @return string[]
     */
    public static function getYearOptions()
    {
        $years = [];
        $min_startdate = Transport::find()->min('start_date');
        if(is_null($min_startdate))
            return null;
        $max_startdate = Transport::find()->max('start_date');
            
        $min_year = DateTime::createFromFormat("Y-m-d", $min_startdate)->format("Y");
        $max_year = DateTime::createFromFormat("Y-m-d", $max_startdate)->format("Y");
        for ($i = $min_year; $i <= $max_year; $i++) {
            $years[$i] = $i;
        }

        return $years;
    }

    public static function getTransportVehicleOptions()
    {
        $transportvehicles = TransportMode::find()->all();
        return ArrayHelper::map($transportvehicles, 'id', 'name');
    }
            
    public static function getPositionUnitsOptions()
    {
        $units = Service::find()->all();
        return ArrayHelper::map($units, 'id', 'name');
    }
    
    public static function getEmployeeOptions()
    {
        $employees = Employee::find()->select(['CONCAT(TRIM(surname), " ", TRIM(name)) as fullname', 'id'])->where(['status' => 1])->where(['deleted' => 0])->orderBy('surname')->asArray()->all();
        return ArrayHelper::map($employees, 'id', 'fullname');
    }
    
    public static function getTransportExpenditureTypeOptions()
    {
        $types = TransportType::find()->all();        
        return ArrayHelper::map($types, 'id', 'name');
    }
    
    public static function getDaysOptions()
    {        
        $days_options = [];
        $min_days = Transport::find()->min('days_applied');
        
        if(is_null($min_days))
            return null;
        $max_days = Transport::find()->max('days_applied');
        
        for ($i = $min_days; $i <= $max_days; $i++) {
            $days_options[$i] = $i;
        }
        
        return $days_options;
    }
    
    /**
     * Ημέρες εκτός έδρας
     * @return NULL|mixed[]|boolean[]|string[]|NULL[]
     */

    public static function getDaysOutOptions()
    {
        $daysout_options = [];
        $min_daysout = Transport::find()->min('days_out');
        
        if(is_null($min_daysout))
            return null;
            $max_daysout = Transport::find()->max('days_out');
            
            for ($i = $min_daysout; $i <= $max_daysout; $i++) {
                $daysout_options[$i] = $i;
            }
            
        return $daysout_options;
    }
    
    
    public static function getNightsOutOptions()
    {
        $nightsout_options = [];
        $min_nightsout = Transport::find()->min('nights_out');
        
        if(is_null($min_nightsout))
            return null;
        $max_nightsout = Transport::find()->max('nights_out');
        
        for ($i = $min_nightsout; $i <= $max_nightsout; $i++) {
            $nightsout_options[$i] = $i;
        }
            
        return $nightsout_options;
    }
    
    /**
     * Returns the group by options of the statistics.
     *
     * @return NULL[]|string[]
     */
    public static function getGroupByOptions()
    {    
        return [ TransportStatistic::GROUPBY_YEAR => Yii::t('app', 'Year'),
            TransportStatistic::GROUPBY_TRANSPORTVEHICLE => Yii::t('app', 'Transport Vehicle'),
            TransportStatistic::GROUPBY_SPECIALISATION => Yii::t('app', 'Specialisation'),
            TransportStatistic::GROUPBY_POSITIONUNIT => Yii::t('app', 'Service'),
            TransportStatistic::GROUPBY_EMPLOYEEOPTION => Yii::t('app', "Employee"),
            TransportStatistic::GROUPBY_EXPENDITURETYPE => Yii::t('app', "Expenditure type"),
            TransportStatistic::GROUPBY_TRANSPORTDAYS => Yii::t('app', "Days number"),
            TransportStatistic::GROUPBY_TRANSPORTDAYSOUT => Yii::t('app', "Away days number"),
            TransportStatistic::GROUPBY_TRANSPORTNIGHTSOUT => Yii::t('app', "Overnights number"),
            ];
    }


    /**
     * Returns the chart type options supported by the statistics.
     *
     * @return NULL[]|string[]
     */
    public static function getChartTypeOptions()
    {
        return [TransportStatistic::CHARTTYPE_BAR => Yii::t('app', "Vertical Bars"),
                TransportStatistic::CHARTTYPE_HORIZONTALBAR => Yii::t('app', "Horizontal Bars"),
                TransportStatistic::CHARTTYPE_DOUGHNUT => Yii::t('app', "Doughnut"),
                TransportStatistic::CHARTTYPE_PIE => Yii::t('app', "Pie"),
                TransportStatistic::CHARTTYPE_POLARAREA => Yii::t('app', "Polar Area")
        ];
    }
}
