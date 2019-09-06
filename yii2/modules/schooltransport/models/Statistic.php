<?php
namespace app\modules\schooltransport\models;

use app\modules\schooltransport\Module;
use DateTime;
use Yii;
use yii\base\Model;

class Statistic extends Model
{
    const GROUPBY_YEAR = 'byYear';
    const GROUPBY_COUNTRY = 'byCountry';
    const GROUPBY_PERFECTURE = 'byPerfecture';
    const GROUPBY_EDULEVEL = 'byEdulevel';
    const GROUPBY_PROGRAM = 'byProgram';

    const CHARTTYPE_BAR = 'bar';
    const CHARTTYPE_HORIZONTALBAR = 'horizontalBar';
    const CHARTTYPE_PIE = 'pie';
    const CHARTTYPE_DOUGHNUT = 'doughnut';
    const CHARTTYPE_POLARAREA = 'polarArea';

    public $statistic_schoolyear;
    public $statistic_country;
    public $statistic_prefecture;
    public $statistic_educationlevel;
    public $statistic_program;
    public $statistic_groupby;
    public $statistic_charttype;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [[['statistic_schoolyear', 'statistic_country', 'statistic_prefecture',
                  'statistic_educationlevel', 'statistic_program'], 'required'],
                [['statistic_schoolyear'], 'each', 'rule' => ['integer']],
                [['statistic_country'], 'string', 'max' => 100],
                [['statistic_prefecture'], 'string', 'max' => 100],
                [['statistic_educationlevel'], 'string', 'max' => 50],
                [['statistic_program'], 'string', 'max' => 300],
                [['statistic_groupby'], 'string', 'max' => 50],
                [['statistic_charttype'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */

    public function attributeLabels()
    {
        return ['statistic_schoolyear' => Module::t('modules/schooltransport/app', 'Σχολικό έτος'),
                'statistic_country' => Module::t('modules/schooltransport/app', 'Χώρα Προορισμού'),
                'statistic_prefecture' => Module::t('modules/schooltransport/app', 'Νομός Εκπαιδευτικής Μονάδας'),
                'statistic_educationlevel' => Module::t('modules/schooltransport/app', 'Βαθμίδα Εκπαίδευσης'),
                'statistic_program' => Module::t('modules/schooltransport/app', 'Πρόγραμμα'),
                'statistic_groupby' => Module::t('modules/schooltransport/app', 'Ομοδοποίηση'),
               ];
    }

    /**
     * Returns the description of the statistic as it has benn defined through the instance variables.
     *
     * @return string
     */
    public function getStatisticLiteral()
    {
        $years = $this->statistic_schoolyear;
        $years_count = count($years);
        $literal = 'Πλήθος σχολικών μετακινήσεων';
        if ($years_count == 1) {
            $years_literal = ' κατά το σχολικό έτος "';
        } else {
            $years_literal = ' κατά τα σχολικά έτη "';
        }
        $prefecture_literal = '';
        $level_literal = '';
        $program_literal = '';
        $country_literal = '';

        $counter = 0;
        foreach ($years as $year) {
            $counter++;
            $years_literal .= ((string)$year .'-' . (string)($year+1));
            if ($counter < $years_count) {
                $years_literal .=  ', ';
            } else {
                $years_literal .= '"';
            }
        }

        if ($this->statistic_educationlevel != 'ALL') {
            $level_literal .= ' της ' . $this->statistic_educationlevel . ' εκπαίδευσης ';
        }

        if ($this->statistic_prefecture != 'ALL') {
            $prefecture_literal .= ' του νομού ' . $this->statistic_prefecture;
        }

        if ($this->statistic_country != 'ALL') {
            $country_article = SchtransportCountry::findOne(['country_name' => $this->statistic_country])['country_accusativearticle'];
            $country_literal .= ' με προορισμό ' . mb_substr($country_article, 1) . ' ' . $this->statistic_country;
        }

        if ($this->statistic_program != 'ALL') {
            $program_literal .= ' στα πλαίσια του προγράμματος ' . $this->statistic_program;
        }

        $literal .= $prefecture_literal . $level_literal . $years_literal . $program_literal . $country_literal . ' ' .
                    '(ομαδοποίηση ' . mb_strtolower(self::getGroupByOptions()[$this->statistic_groupby]) . ')';

        return $literal;
    }

    /**
     * Returns the statistics based on the values of the instance variables.
     *
     * @return array
     */
    public function getStatistics()
    {
        $tblprefix = Yii::$app->db->tablePrefix;
        $t = $tblprefix . 'schtransport_transport';
        $m = $tblprefix . 'schtransport_meeting';
        $d = $tblprefix . 'directorate';
        $pc = $tblprefix . 'schtransport_programcategory';

        $groupby_options = Statistic::getGroupByOptions();
        $andWhereCondition = '';
        $data = [];
        $index = 0;
        if ($this->statistic_groupby == Statistic::GROUPBY_YEAR) {
            $school_years = Statistic::getSchoolYearOptions();
            foreach ($school_years as $school_year => $literal) {
                $andWhereCondition = $t . ".transport_startdate >= '" . $school_year . "-09-01' AND " .
                                     $t . ".transport_startdate <= '" . (string)($school_year+1) . "-08-31'";
                $data['LABELS'][$index] = $school_year;
                $data['TRANSPORTS_COUNT'][$index] = Statistic::countTransports($andWhereCondition);
                $index++;
            }
        } elseif ($this->statistic_groupby == Statistic::GROUPBY_COUNTRY) {
            $countries = Statistic::getCountryOptions();
            foreach ($countries as $country) {
                $andWhereCondition = $m . ".meeting_country='" . $country . "'";
                $data['LABELS'][$index] = $country;
                $data['TRANSPORTS_COUNT'][$index] = Statistic::countTransports($andWhereCondition);
                $index++;
            }
        } elseif ($this->statistic_groupby == Statistic::GROUPBY_EDULEVEL) {
            $edulevels = Statistic::getEducationalLevelOptions();
            foreach ($edulevels as $edulevel) {
                $andWhereCondition = $d . ".directorate_name LIKE '%" . $edulevel . "%'";
                $data['LABELS'][$index] = $edulevel;
                $data['TRANSPORTS_COUNT'][$index] = Statistic::countTransports($andWhereCondition);
                $index++;
            }
        } elseif ($this->statistic_groupby == Statistic::GROUPBY_PROGRAM) {
            $program_categs = Statistic::getProgramCategoryOptions();
            foreach ($program_categs as $program_alias => $program_title) {
                $andWhereCondition = $pc . ".programcategory_programalias='" . $program_alias . "'";
                $transports_count = Statistic::countTransports($andWhereCondition);
                if ($transports_count > 0) {
                    $data['LABELS'][$index] = $program_title;
                    $data['TRANSPORTS_COUNT'][$index] = $transports_count;
                    $index++;
                }
            }
        } else {//if($this->statistic_groupby == Statistic::GROUPBY_PERFECTURE){
            $prefectures = Statistic::getPrefectureOptions();
            foreach ($prefectures as $prefecture) {
                $andWhereCondition = $d . ".directorate_name LIKE '%" . $prefecture . "%'";
                $data['LABELS'][$index] = $prefecture;
                $data['TRANSPORTS_COUNT'][$index] = Statistic::countTransports($andWhereCondition);
                $index++;
            }
        }

        return $data;
    }

    /**
     * Returns the school years options based on the dates of the school transports saved in the database.
     *
     * @return string[]
     */
    public static function getSchoolYearOptions()
    {
        $school_years = [];
        $min_startdate = SchtransportTransport::find()->min('transport_startdate');
        if(is_null($min_startdate))
            return null;
        $max_startdate = SchtransportTransport::find()->max('transport_startdate');
            
        $min_year = Statistic::getSchoolYearOf(DateTime::createFromFormat("Y-m-d", $min_startdate));
        $max_year = Statistic::getSchoolYearOf(DateTime::createFromFormat("Y-m-d", $max_startdate));
        for ($i = $min_year; $i <= $max_year; $i++) {
            $school_years[$i] = (string)$i . '-' . (string)($i+1);
        }

        return $school_years;
    }

    /**
     * Returns an array with the prefectures options.
     *
     * @return string[]
     */
    public static function getPrefectureOptions()
    {
        return ['Ηρακλείου' => 'Ηρακλείου', 'Λασιθίου' => 'Λασιθίου',
                'Ρεθύμνου' => 'Ρεθύμνου', 'Χανίων' => 'Χανίων'];
    }

    /**
     * Returns the educational levels options.
     *
     * @return string[]
     */
    public static function getEducationalLevelOptions()
    {
        return ['Πρωτοβάθμιας' => 'Πρωτοβάθμια', 'Δευτεροβάθμιας' => 'Δευτεροβάθμια'];
    }

    /**
     * Returns the country options based on the countries for which there is a school transport in the database.
     *
     * @return array
     */
    public static function getCountryOptions()
    {
        return SchtransportMeeting::find()->select('meeting_country')->distinct()->orderBy('meeting_country')->indexBy('meeting_country')->column();
    }

    /**
     * Returns the group by options of the statistics.
     *
     * @return NULL[]|string[]
     */
    public static function getGroupByOptions()
    {
        return [ Statistic::GROUPBY_YEAR => Module::t('modules/schooltransport/app', "By school year"),
            Statistic::GROUPBY_COUNTRY => Module::t('modules/schooltransport/app', "By country"),
            Statistic::GROUPBY_PERFECTURE => Module::t('modules/schooltransport/app', "By perfecture"),
            Statistic::GROUPBY_EDULEVEL => Module::t('modules/schooltransport/app', "By education level"),
            Statistic::GROUPBY_PROGRAM => Module::t('modules/schooltransport/app', "By program category")
        ];
    }

    /**
     * Retrurns the program category options.
     *
     * @return string[]
     */
    public static function getProgramCategoryOptions()
    {
        $program_options = [];
        $programcategs = SchtransportProgramcategory::find()->orderBy('programcategory_programtitle')->all();
        foreach ($programcategs as $programcateg) {
            $program_children = SchtransportProgramcategory::findAll(['programcategory_programparent' => $programcateg->programcategory_programalias]);
            if (count($program_children) == 0) {
                $program_options[$programcateg->programcategory_programalias] = $programcateg->programcategory_programtitle;
            }
        }
        return $program_options;
    }

    /**
     * Returns the chart type options supported by the statistics.
     *
     * @return NULL[]|string[]
     */
    public static function getChartTypeOptions()
    {
        return [Statistic::CHARTTYPE_BAR => Module::t('modules/schooltransport/app', "Vertical Bars"),
                Statistic::CHARTTYPE_HORIZONTALBAR => Module::t('modules/schooltransport/app', "Horizontal Bars"),
                Statistic::CHARTTYPE_DOUGHNUT => Module::t('modules/schooltransport/app', "Doughnut"),
                Statistic::CHARTTYPE_PIE => Module::t('modules/schooltransport/app', "Pie"),
                Statistic::CHARTTYPE_POLARAREA => Module::t('modules/schooltransport/app', "Polar Area")
        ];
    }

    /**
     * Returns the number of transports based on the condition passed as parameter.
     *
     * @param string $andWhereCondition
     */
    protected function countTransports($andWhereCondition)
    {
        $tblprefix = Yii::$app->db->tablePrefix;
        $t = $tblprefix . 'schtransport_transport';
        $m = $tblprefix . 'schtransport_meeting';
        $s = $tblprefix . 'schoolunit';
        $d = $tblprefix . 'directorate';
        $p = $tblprefix . 'schtransport_program';
        $pc = $tblprefix . 'schtransport_programcategory';

        $query = (new \yii\db\Query())
                ->select("COUNT(" . $t . ".transport_id) AS TRNSPRTS_COUNT")
                ->from($t . "," . $m . "," . $s . "," . $d . "," . $p . "," . $pc)
                ->where($t . ".meeting_id=" . $m . ".meeting_id")
                ->andWhere($m . ".program_id=" . $p. ".program_id")
                ->andWhere($p . ".programcategory_id=" . $pc . ".programcategory_id")
                ->andWhere($t . ".school_id=" . $s . ".school_id")
                ->andWhere($s . ".directorate_id=" . $d . ".directorate_id");

        if ($this->statistic_country != 'ALL') {
            $query = $query->andWhere($m . ".meeting_country='" . $this->statistic_country . "'");
        }

        if ($this->statistic_prefecture != 'ALL') {
            $query = $query->andWhere($d . ".directorate_name LIKE '%" . $this->statistic_prefecture . "%'");
        }

        if ($this->statistic_educationlevel != 'ALL') {
            $query = $query->andWhere($d . ".directorate_name LIKE '%" . $this->statistic_educationlevel . "%'");
        }

        if ($this->statistic_program != 'ALL') {
            $query = $query->andWhere($pc . ".programcategory_programalias='" . $this->statistic_program . "'");
        }

        $firstyear_flag = false;
        foreach ($this->statistic_schoolyear as $school_year) {
            if (!$firstyear_flag) {
                $subquery = "(" . $t . ".transport_startdate >= '" . $school_year . "-09-01' AND " .
                            $t . ".transport_startdate <= '" . (string)($school_year+1) . "-08-31')";
                $firstyear_flag = true;
            } else {
                $subquery .= " OR " . "(" . $t . ".transport_startdate >= '" . $school_year . "-09-01' AND " .
                                           $t . ".transport_startdate <= '" . (string)($school_year+1) . "-08-31')";
            }
        }
        $query = $query->andWhere($subquery);
        $query = $query->andWhere($andWhereCondition);
        return $query->one()['TRNSPRTS_COUNT'];
    }


    /*
     * Returns the starting year of the school year expressed by $date.
     * For example if $date is 21/02/2018 the return value will be 2017.
     * If $date is 15/09/2018 the return value is 2018.
     */
    public static function getSchoolYearOf($date)
    {
        $year = $date->format("Y");
        $month = $date->format("m");
        $day = $date->format("d");
        if (!checkdate($month, $day, $year)) {
            return -1;
        }
        return ($month >= 9) ? $year : $year-1;
    }
}
