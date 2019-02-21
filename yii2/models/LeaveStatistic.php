<?php
namespace app\models;

use DateTime;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use app\modules\eduinventory\components\EduinventoryHelper;

class LeaveStatistic extends Model
{
    const GROUPBY_YEAR = 'byYear';
    const GROUPBY_LEAVETYPE = 'byLeavetype';
    const GROUPBY_SPECIALISATION = 'bySpecialisation';
    const GROUPBY_POSITIONTITLE = 'byPositiontitle';
    const GROUPBY_POSITIONUNIT = 'byPositionunit';
    const GROUPBY_EMPLOYEEOPTION = 'byEmployee';

    const CHARTTYPE_BAR = 'bar';
    const CHARTTYPE_HORIZONTALBAR = 'horizontalBar';
    const CHARTTYPE_PIE = 'pie';
    const CHARTTYPE_DOUGHNUT = 'doughnut';
    const CHARTTYPE_POLARAREA = 'polarArea';

    public $statistic_year;
    public $statistic_leavetype;
    public $statistic_specialisation;
    public $statistic_positiontitle;
    public $statistic_positionunit;
    public $statistic_employee;
    public $statistic_groupby;
    public $statistic_charttype;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [[['statistic_year', 'statistic_leavetype', 'statistic_specialisation',
                  'statistic_positiontitle', 'statistic_positionunit', 'statistic_employee'], 'required'],
                [['statistic_year'], 'each', 'rule' => ['integer']],
                [['statistic_leavetype'], 'string'],
                [['statistic_specialisation'], 'string'],
                [['statistic_positiontitle'], 'string'],
                [['statistic_positionunit'], 'string'],
                [['statistic_employee'], 'string'],
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
                'statistic_leavetype' => Yii::t('app', 'Leave Type'),
                'statistic_specialisation' => Yii::t('app', 'Specialisation'),
                'statistic_positiontitle' => Yii::t('app', 'Position'),
                'statistic_positionunit' => Yii::t('app', 'Service'),
                'statistic_employee' => Yii::t('app', 'Employee'),
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
        $standard_phrase = 'Πλήθος αδειών';
        if ($years_count == 1) {
            $years_part = ' κατά το έτος "';
        } else {
            $years_part = ' κατά τα έτη "';
        }
        /* public $statistic_year;
        public $statistic_leavetype;
        public $statistic_specialisation;
        public $statistic_positiontitle;
        public $statistic_positionunit;
        public $statistic_employee;
        public $statistic_groupby;
        public $statistic_charttype; */       

        $leavetype_part = '';
        $teachers_part = '';
        $specialisation_part = '';
        $positiontitle_part = '';
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

        if ($this->statistic_leavetype != 'ALL') {
            $leavetype_part .= ' τύπου "' . LeaveType::findOne(['id' => $this->statistic_leavetype])['name'] . '"';
        }

        if ($this->statistic_specialisation != 'ALL' || $this->statistic_positiontitle != 'ALL' || $this->statistic_positionunit != 'ALL')
            $teachers_part .= " εκπαιδευτικών";
        
        if ($this->statistic_specialisation != 'ALL') {                
            $specialisation_part .= ' ειδικότητας "' . Specialisation::findOne(['id', $this->statistic_specialisation])['code'] . '"';
            $putcomma = true;
        }

        if ($this->statistic_positiontitle != 'ALL') {
            if($putcomma)
                $positiontitle_part .= ',';
            $positiontitle_part .= ' θέσης "' . Position::findOne(['id' => $this->statistic_positiontitle])['name'] . '"';
            $putcomma = true;
        }

        if ($this->statistic_positionunit != 'ALL') {
            if($putcomma)
                $positionunit_part .= ',';
            $positionunit_part .= ' υπηρετούντες στην υπηρεσία "' . Service::findOne(['id' => $this->statistic_positionunit])['name'] . '"';
        }

        $standard_phrase .= $years_part . $leavetype_part . $teachers_part . $specialisation_part . $positiontitle_part . $positionunit_part . ' ' .
                    '(ομαδοποίηση ανά ' . mb_strtolower(self::getGroupByOptions()[$this->statistic_groupby]) . ')';

        return $standard_phrase; 
    }

    /**
     * Returns the statistics based on the values of the instance variables.
     *
     * @return array
     */
    public function getStatistics()
    {//echo "<pre>"; print_r($this); echo "</pre>"; die();
        $tblprefix = Yii::$app->db->tablePrefix;
        $l = $tblprefix . 'leave';
        $pt = $tblprefix . 'position';
        $pu = $tblprefix . 'service';
        $lt = $tblprefix . 'leave_type';
        $specs = $tblprefix . 'specialisation';
        $emps = $tblprefix . 'employee';

        $groupby_options = LeaveStatistic::getGroupByOptions();
        $andWhereCondition = '';
        $data = [];
        $index = 0;
        //echo "<pre>"; print_r(LeaveStatistic::getYearOptions()); echo "<pre>"; die();
        if ($this->statistic_groupby == LeaveStatistic::GROUPBY_YEAR) {
            $years = $this->statistic_year;//LeaveStatistic::getYearOptions();
            foreach ($years as $year) {
                $andWhereCondition = $l . ".start_date >= '" . $year . "-01-01' AND " .
                                     $l . ".start_date <= '" . $year . "-12-31'";
                $data['LABELS'][$index] = $year;
                $data['LEAVES_COUNT'][$index] = LeaveStatistic::countLeaves($andWhereCondition);
                $index++;
            }
        } elseif ($this->statistic_groupby == LeaveStatistic::GROUPBY_LEAVETYPE) {
            $leavetypes = LeaveStatistic::getLeaveTypeOptions();
            foreach ($leavetypes as $typeid=>$leavename) {
                $andWhereCondition = $l . ".type=" . $typeid;
                $leaves_count = LeaveStatistic::countLeaves($andWhereCondition);
                if ($leaves_count != 0) {
                    $data['LABELS'][$index] = $leavename;
                    $data['LEAVES_COUNT'][$index] = $leaves_count;
                    $index++;
                }
            }
        } elseif ($this->statistic_groupby == LeaveStatistic::GROUPBY_SPECIALISATION) {
            $specialisations = EduinventoryHelper::getSpecializations();            
            foreach ($specialisations as $specialisation_id => $specialisation_code) {
                $andWhereCondition = $specs . ".id=" . $specialisation_id;
                $leaves_count = LeaveStatistic::countLeaves($andWhereCondition);
                if ($leaves_count != 0) {
                    $data['LABELS'][$index] = $specialisation_code;
                    $data['LEAVES_COUNT'][$index] = $leaves_count;
                    $index++;
                }
            }
        } elseif ($this->statistic_groupby == LeaveStatistic::GROUPBY_POSITIONTITLE) {
            $positiontitles = LeaveStatistic::getPositionTitlesOptions();
            foreach ($positiontitles as $id => $positiontitle) {
                $andWhereCondition = $pt . ".id=" . $id;
                $leaves_count = LeaveStatistic::countLeaves($andWhereCondition);
                if ($leaves_count != 0) {
                    $data['LABELS'][$index] = $this->reduceLength($positiontitle);
                    $data['LEAVES_COUNT'][$index] = $leaves_count;
                    $index++;
                }
            }
        } elseif($this->statistic_groupby == LeaveStatistic::GROUPBY_POSITIONUNIT){
            $positionunits = LeaveStatistic::getPositionUnitsOptions();
            foreach ($positionunits as $id=>$positionunit) {
                $andWhereCondition = $pu . ".id=" . $id;
                $leaves_count = LeaveStatistic::countLeaves($andWhereCondition);
                if($leaves_count != 0) {
                    $data['LABELS'][$index] = $this->reduceLength($positionunit);
                    $data['LEAVES_COUNT'][$index] = $leaves_count;
                    $index++;
                }
            }
        } else {
            $employees = LeaveStatistic::getEmployeeOptions();
            foreach ($employees as $id=>$employee_surname) {
                $andWhereCondition = $emps . ".id = " . $id;
                $leaves_count = LeaveStatistic::countLeaves($andWhereCondition);
                if ($leaves_count != 0) {
                    $data['LABELS'][$index] = $employee_surname;
                    $data['LEAVES_COUNT'][$index] = $leaves_count;
                    $index++;
                }
            }
        }//die();//echo "<pre>"; print_r($data); echo "</pre>"; die();
        
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
    protected function countLeaves($andWhereCondition)
    {
        $tblprefix = Yii::$app->db->tablePrefix;
        $l = $tblprefix . 'leave';
        $lt = $tblprefix . 'leave_type';
        $e = $tblprefix . 'employee';
        $es = $tblprefix . 'employee_status';
        $pt = $tblprefix . 'position';
        $pu = $tblprefix . 'service';
        $esp = $tblprefix . 'specialisation';
        
        $query = (new \yii\db\Query())
        ->select("SUM(" . $l . ".duration) AS LEAVES_COUNT")
        ->from($l . "," . $lt . "," . $e . "," . $es . "," .  $esp . "," . $pt . "," . $pu)
        ->where($l . ".deleted=0")
        ->andWhere($l . ".type=" . $lt . ".id")
        ->andWhere($l . ".employee=" . $e. ".id")
        ->andWhere($e . ".specialisation=" . $esp . ".id")
        ->andWhere($e . ".status=" . $es . ".id")
        ->andWhere($e . ".position=" . $pt . ".id")
        ->andWhere($e . ".service_serve=" . $pu . ".id");
        
        if ($this->statistic_leavetype != 'ALL') {
            $query = $query->andWhere($l . ".type=" . $this->statistic_leavetype);
        }
        
        if ($this->statistic_positiontitle != 'ALL') {
            $query = $query->andWhere($pt . ".id=" . $this->statistic_positiontitle);
        }
        
        if ($this->statistic_positionunit != 'ALL') {
            $query = $query->andWhere($pu . ".id=" . $this->statistic_positionunit);
        }
        
        if ($this->statistic_specialisation != 'ALL') {
            $query = $query->andWhere($e . ".specialisation=" . $this->statistic_specialisation);
        }
        
        if ($this->statistic_employee != 'ALL') {
            $query = $query->andWhere($e . ".id=" . $this->statistic_employee);
        }
        
        $firstyear_flag = false;
        foreach ($this->statistic_year as $year) {
            if (!$firstyear_flag) {
                $subquery = "(" . $l . ".start_date >= '" . $year . "-01-01' AND " . $l . ".start_date <= '" . $year . "-12-31')";
                $firstyear_flag = true;
            } else {
                $subquery .= " OR " . "(" . $l . ".start_date >= '" . $year . "-01-01' AND " .
                    $l . ".start_date <= '" . $year . "-12-31')";
            }
        }
        $query = $query->andWhere($subquery);
        $query = $query->andWhere($andWhereCondition);
        //echo $query->createCommand()->rawSql . "<br /><br />";  //die();
        return $query->one()['LEAVES_COUNT'];
    }
    
    /**
     * Returns the year options based on the dates of the leaves that exist in the database.
     *
     * @return string[]
     */
    public static function getYearOptions()
    {
        $years = [];
        $min_startdate = Leave::find()->min('start_date');
        if(is_null($min_startdate))
            return null;
        $max_startdate = Leave::find()->max('start_date');
            
        $min_year = DateTime::createFromFormat("Y-m-d", $min_startdate)->format("Y");
        $max_year = DateTime::createFromFormat("Y-m-d", $max_startdate)->format("Y");
        for ($i = $min_year; $i <= $max_year; $i++) {
            $years[$i] = $i;
        }

        return $years;
    }

    public static function getLeaveTypeOptions()
    {
        $leavetypes = LeaveType::find()->all();
        return ArrayHelper::map($leavetypes, 'id', 'name');
    }
        
    public static function getPositionTitlesOptions()
    {
        $positions = Position::find()->all();
        return ArrayHelper::map($positions, 'id', 'name');
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
    
    
    /**
     * Returns the group by options of the statistics.
     *
     * @return NULL[]|string[]
     */
    public static function getGroupByOptions()
    {         
        return [ LeaveStatistic::GROUPBY_YEAR => Yii::t('app', 'Year'),
            LeaveStatistic::GROUPBY_LEAVETYPE => Yii::t('app', 'Leave Type'),
            LeaveStatistic::GROUPBY_SPECIALISATION => Yii::t('app', 'Specialisation'),
            LeaveStatistic::GROUPBY_POSITIONTITLE => Yii::t('app', 'Position'),
            LeaveStatistic::GROUPBY_POSITIONUNIT => Yii::t('app', 'Service'),
                LeaveStatistic::GROUPBY_EMPLOYEEOPTION => Yii::t('app', "Employee")
            ];
    }


    /**
     * Returns the chart type options supported by the statistics.
     *
     * @return NULL[]|string[]
     */
    public static function getChartTypeOptions()
    {
        return [LeaveStatistic::CHARTTYPE_BAR => Yii::t('app', "Vertical Bars"),
                LeaveStatistic::CHARTTYPE_HORIZONTALBAR => Yii::t('app', "Horizontal Bars"),
                LeaveStatistic::CHARTTYPE_DOUGHNUT => Yii::t('app', "Doughnut"),
                LeaveStatistic::CHARTTYPE_PIE => Yii::t('app', "Pie"),
                LeaveStatistic::CHARTTYPE_POLARAREA => Yii::t('app', "Polar Area")
        ];
    }
}
