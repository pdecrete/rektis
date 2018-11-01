<?php
namespace app\modules\disposal\models;

use app\modules\eduinventory\components\EduinventoryHelper;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use app\modules\disposal\DisposalModule;
use Yii;

class DisposalStatistic extends Model
{
    const GROUPBY_YEAR = 1;
    const GROUPBY_EDULEVEL = 2;
    const GROUPBY_PERFECTURE = 3;
    const GROUPBY_SPECIALIZATION = 4;
    const GROUPBY_DUTY = 5;
    const GROUPBY_REASON = 6;
    
    const CHARTTYPE_BAR = 'bar';
    const CHARTTYPE_HORIZONTALBAR = 'horizontalBar';
    const CHARTTYPE_PIE = 'pie';
    const CHARTTYPE_DOUGHNUT = 'doughnut';
    const CHARTTYPE_POLARAREA = 'polarArea';
    
    public $statistic_schoolyear;
    public $statistic_educationlevel;
    public $statistic_prefecture;
    public $statistic_specialization;
    public $statistic_duty;
    public $statistic_reason;
    public $statistic_groupby;
    public $statistic_charttype;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [[['statistic_schoolyear', 'statistic_educationlevel', 'statistic_prefecture',
            'statistic_specialization', 'statistic_reason'], 'required'],
            [['statistic_schoolyear'], 'each', 'rule' => ['integer']],
            [['statistic_educationlevel'], 'string', 'max' => 100],
            [['statistic_prefecture'], 'string', 'max' => 100],
            [['statistic_specialization'], 'string', 'max' => 50],
            [['statistic_duty'], 'string', 'max' => 300],
            [['statistic_reason'], 'string', 'max' => 300],
            [['statistic_groupby'], 'string', 'max' => 50],
            [['statistic_charttype'], 'string', 'max' => 50]
        ];
    }
    
    /**
     * @inheritdoc
     */
    
    public function attributeLabels()
    {
        return ['statistic_schoolyear' => DisposalModule::t('modules/disposal/app', 'Σχολικό έτος'),
            'statistic_educationlevel' => DisposalModule::t('modules/disposal/app', 'Βαθμίδα Εκπαίδευσης'),
            'statistic_prefecture' => DisposalModule::t('modules/disposal/app', 'Νομός Εκπαιδευτικής Μονάδας'),
            'statistic_specialization' => DisposalModule::t('modules/disposal/app', 'Ειδικότητα Διατιθέμενου Εκπαιδευτικού'),
            'statistic_reason' => DisposalModule::t('modules/disposal/app', 'Λόγος Διάθεσης'),
            'statistic_duty' => DisposalModule::t('modules/disposal/app', 'Καθήκοντα Διάθεσης'),
            'statistic_groupby' => DisposalModule::t('modules/disposal/app', 'Ομοδοποίηση'),
            'statistic_charttype' => DisposalModule::t('modules/disposal/app', 'Τύπος Γραφήματος'),
        ];
    }
    
    
    public function getStatistics()
    {
        //echo "<pre>"; print_r($this); echo "</pre>"; die();
        $tblprefix = Yii::$app->db->tablePrefix;
        $dsp = $tblprefix . 'disposal_disposal';
        $drs = $tblprefix . 'disposal_disposalreason';
        $dir = $tblprefix . 'directorate';
        $dr = $tblprefix . 'disposal_disposalworkobj';
        $specs = $tblprefix . 'specialisation';
        $schl = $tblprefix . 'schoolunit';

        
        $groupby_options = DisposalStatistic::getGroupByOptions();
        $andWhereCondition = '';
        $data = [];
        $index = 0;

        if ($this->statistic_groupby == DisposalStatistic::GROUPBY_YEAR) {
            $school_years = DisposalStatistic::getSchoolYearOptions();
            foreach ($school_years as $school_year => $literal) {
                $andWhereCondition = $dsp . ".disposal_startdate >= '" . $school_year . "-09-01' AND " .
                    $dsp . ".disposal_startdate <= '" . (string)($school_year+1) . "-08-31'";
                    $data['LABELS'][$index] = $school_year;
                    $data['DISPOSALS_COUNT'][$index] = DisposalStatistic::countDisposals($andWhereCondition);
                    $index++;
            }
        } elseif ($this->statistic_groupby == DisposalStatistic::GROUPBY_REASON) {
            $reasons = DisposalStatistic::getReasonOptions();
            foreach ($reasons as $reason_id => $reason_description) {
                $andWhereCondition = $dsp . ".disposalreason_id='" . $reason_id . "'";
                $data['LABELS'][$index] = $reason_description;
                $data['DISPOSALS_COUNT'][$index] = DisposalStatistic::countDisposals($andWhereCondition);
                $index++;
            }
        } elseif ($this->statistic_groupby == DisposalStatistic::GROUPBY_EDULEVEL) {
            $edulevels = EduinventoryHelper::getEducationalLevels();
            //echo "<pre>";print_r($edulevels);echo "<pre>";die();
            foreach ($edulevels as $key=>$edulevel) {
                $andWhereCondition = $dir . ".directorate_stage LIKE '%" . $key . "%'";
                $data['LABELS'][$index] = $edulevel;
                $data['DISPOSALS_COUNT'][$index] = DisposalStatistic::countDisposals($andWhereCondition);
                $index++;
            }
            $andWhereCondition = $dir . ".directorate_stage IS NULL";
            $data['LABELS'][$index] = "Ανεξάρτητων Δομών";
            $data['DISPOSALS_COUNT'][$index] = DisposalStatistic::countDisposals($andWhereCondition);
            $index++;
        } elseif ($this->statistic_groupby == DisposalStatistic::GROUPBY_SPECIALIZATION) {
            $specializations = EduinventoryHelper::getSpecializations();
            foreach ($specializations as $specialization_id => $specialization_code) {
                $andWhereCondition = $specs . ".id='" . $specialization_id . "'";                
                $disposals_count = DisposalStatistic::countDisposals($andWhereCondition);
                if($disposals_count != 0) {
                    $data['LABELS'][$index] = $specialization_code;
                    $data['DISPOSALS_COUNT'][$index] = $disposals_count;
                    $index++;
                }                
            }
        } elseif ($this->statistic_groupby == DisposalStatistic::GROUPBY_DUTY) {
            $duties = DisposalStatistic::getDutyOptions();
            foreach ($duties as $duty_id => $duty_description) {
                $andWhereCondition = $dr . ".disposalworkobj_id='" . $duty_id . "'";
                $data['LABELS'][$index] = $duty_description;
                $data['DISPOSALS_COUNT'][$index] = DisposalStatistic::countDisposals($andWhereCondition);
                $index++;
            }
        } else {//if($this->statistic_groupby == Statistic::GROUPBY_PREFECTURES){
            $prefectures = EduinventoryHelper::getPrefectures();
            $disposals_count = 0;
            foreach ($prefectures as $prefecture) {
                $andWhereCondition = "(" . $dir . ".directorate_name LIKE '%" . $prefecture . "%' OR " . $schl . ".school_name LIKE '%" . $prefecture . "%')";
                $disposals_count += DisposalStatistic::countDisposals($andWhereCondition);
                $data['LABELS'][$index] = $prefecture;
                $data['DISPOSALS_COUNT'][$index] = DisposalStatistic::countDisposals($andWhereCondition);
                $index++;
            }
            $more_disposals = DisposalStatistic::countDisposals("") - $disposals_count;
            if($more_disposals > 0) {            
                $data['LABELS'][$index] = "Άγνωστο";
                $data['DISPOSALS_COUNT'][$index] = $more_disposals;
                $index++;
            }
        }
        return $data;
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
        $literal = 'Πλήθος διαθέσεων';
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
                
        return $literal;
    }
    
    /**
     * Returns the school years options based on the dates of the disposals saved in the database.
     *
     * @return string[]
     */
    public static function getSchoolYearOptions()
    {
        $school_years = [];
        $min_startdate = Disposal::find()->where(['archived' => 1])->min('disposal_startdate');
        if(is_null($min_startdate))
            return null;
        $max_startdate = Disposal::find()->where(['archived' => 1])->max('disposal_startdate');
            
        $min_year = EduinventoryHelper::getSchoolYearOf($min_startdate);
        $max_year = EduinventoryHelper::getSchoolYearOf($max_startdate);
        for ($i = $min_year; $i <= $max_year; $i++) {
            $school_years[$i] = (string)$i . '-' . (string)($i+1);
        }
            
        return $school_years;
    }
    
    
    /**
     * Returns the chart type options supported by the statistics.
     *
     * @return |string[]
     */
    public static function getChartTypeOptions()
    {
        return [DisposalStatistic::CHARTTYPE_BAR => DisposalModule::t('modules/disposal/app', "Vertical Bars"),
            DisposalStatistic::CHARTTYPE_HORIZONTALBAR => DisposalModule::t('modules/disposal/app', "Horizontal Bars"),
            DisposalStatistic::CHARTTYPE_DOUGHNUT => DisposalModule::t('modules/disposal/app', "Doughnut"),
            DisposalStatistic::CHARTTYPE_PIE => DisposalModule::t('modules/disposal/app', "Pie"),
            DisposalStatistic::CHARTTYPE_POLARAREA => DisposalModule::t('modules/disposal/app', "Polar Area")
        ];
    }
    
    
    /**
     * Returns the duty options for a disposal
     * 
     * return string[]
     */
    public static function getDutyOptions()
    {   
        return ArrayHelper::map(DisposalWorkobj::find()->all(), 'disposalworkobj_id', 'disposalworkobj_description');
    }
    
    
    /**
     * Returns the reason options for a disposal
     *
     * return string[]
     */
    public static function getReasonOptions()
    {
        return ArrayHelper::map(DisposalReason::find()->all(), 'disposalreason_id', 'disposalreason_description');
    }
    
    
    /**
     * Returns the number of disposals based on the condition passed as parameter.
     *
     * @param string $andWhereCondition
     */
    protected function countDisposals($andWhereCondition)
    {
        $tblprefix = Yii::$app->db->tablePrefix;
        $dsp = $tblprefix . 'disposal_disposal';
        $drs = $tblprefix . 'disposal_disposalreason';
        $ddt = $tblprefix . 'disposal_disposalworkobj';
        $tchr = $tblprefix . 'teacher';
        $schl = $tblprefix . 'schoolunit';
        $specs = $tblprefix . 'specialisation';

        $dir = $tblprefix . 'directorate';
        
        $query = (new \yii\db\Query())
        ->select("COUNT(" . $dsp . ".disposal_id) AS DISPOSALS_COUNT")
        ->from($dsp . "," . $drs . "," . $ddt . "," . $tchr . "," . $schl . "," . $dir . "," . $specs)
        ->where($dsp . ".archived=1")
        ->andWhere($dsp . ".disposalreason_id=" . $drs . ".disposalreason_id")
        ->andWhere($dsp . ".disposalworkobj_id=" . $ddt . ".disposalworkobj_id")
        ->andWhere($dsp . ".teacher_id=" . $tchr . ".teacher_id")
        ->andWhere($tchr . ".school_id=" . $schl . ".school_id")
        ->andWhere($dir . ".directorate_id=" . $schl . ".directorate_id")
        ->andWhere($tchr . ".specialisation_id=" . $specs . ".id");
        //echo $query->createCommand()->rawSql; die();
        $duties['ALL'] = DisposalModule::t('modules/disposal/app', 'Όλα τα καθήκοντα');
        $duties = DisposalStatistic::getDutyOptions();
        $reasons['ALL'] = DisposalModule::t('modules/disposal/app', 'Όλοι οι λόγοι');
        $reasons = DisposalStatistic::getReasonOptions();
        $specializations['ALL'] = DisposalModule::t('modules/disposal/app', 'Όλες οι ειδικότητες');
        $specializations = EduinventoryHelper::getSpecializations();
        $groupby_options = DisposalStatistic::getGroupByOptions();
        
        
        if ($this->statistic_prefecture != 'ALL') {
            $query = $query->andWhere($dir . ".directorate_name LIKE '%" . $this->statistic_prefecture . "%'");
        }
        
        if ($this->statistic_educationlevel != 'ALL') {
            $query = $query->andWhere($dir . ".directorate_name LIKE '%" . $this->statistic_educationlevel . "%'");
        }
        
        if ($this->statistic_duty != 'ALL') {
            $query = $query->andWhere($ddt . ".disposalworkobj_id=" . $this->statistic_duty);
        }
        
        if ($this->statistic_reason != 'ALL') {
            $query = $query->andWhere($drs . ".disposalreason_id=" . $this->statistic_reason);
        }
        
        if ($this->statistic_specialization != 'ALL') {
            $query = $query->andWhere($specs . ".id=" . $this->statistic_specialization);
        }
        
        $firstyear_flag = false;
        foreach ($this->statistic_schoolyear as $school_year) {
            if (!$firstyear_flag) {
                $subquery = "(" . $dsp . ".disposal_startdate >= '" . $school_year . "-09-01' AND " .
                    $dsp . ".disposal_startdate <= '" . (string)($school_year+1) . "-08-31')";
                    $firstyear_flag = true;
            } else {
                $subquery .= " OR " . "(" . $dsp . ".disposal_startdate >= '" . $school_year . "-09-01' AND " .
                    $dsp . ".disposal_startdate <= '" . (string)($school_year+1) . "-08-31')";
            }
        }
        $query = $query->andWhere($subquery);
        $query = $query->andWhere($andWhereCondition);
        //echo $query->createCommand()->rawSql;
        return $query->one()['DISPOSALS_COUNT'];
    }
    
    
    /**
     * Returns the group by options of the statistics.
     *
     * @return string[]
     */
    public static function getGroupByOptions()
    {   
        return [ DisposalStatistic::GROUPBY_YEAR => DisposalModule::t('modules/disposal/app', "By school year"),
            DisposalStatistic::GROUPBY_EDULEVEL => DisposalModule::t('modules/disposal/app', "By education level"),
            DisposalStatistic::GROUPBY_PERFECTURE => DisposalModule::t('modules/disposal/app', "By perfecture"),
            DisposalStatistic::GROUPBY_SPECIALIZATION => DisposalModule::t('modules/disposal/app', "By specialization"),
            DisposalStatistic::GROUPBY_DUTY => DisposalModule::t('modules/disposal/app', "By disposal duty"),
            DisposalStatistic::GROUPBY_REASON => DisposalModule::t('modules/disposal/app', "By disposal reason")
        ];
    }
}

