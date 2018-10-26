<?php
namespace app\modules\disposal\models;

use app\modules\eduinventory\components\EduinventoryHelper;
use yii\base\Model;
use app\modules\disposal\DisposalModule;

class Statistic extends Model
{
    const GROUPBY_YEAR = 1;
    const GROUPBY_EDULEVEL = 5;
    const GROUPBY_PERFECTURE = 6;
    const GROUPBY_SPECIALIAZATION = 4;
    const GROUPBY_DUTY = 2;
    const GROUPBY_REASON = 3;
    
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

    
    /**
     * Returns the school years options based on the dates of the school transports saved in the database.
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
     * @return NULL[]|string[]
     */
    public static function getChartTypeOptions()
    {
        return [Statistic::CHARTTYPE_BAR => DisposalModule::t('modules/disposal/app', "Vertical Bars"),
            Statistic::CHARTTYPE_HORIZONTALBAR => DisposalModule::t('modules/disposal/app', "Horizontal Bars"),
            Statistic::CHARTTYPE_DOUGHNUT => DisposalModule::t('modules/disposal/app', "Doughnut"),
            Statistic::CHARTTYPE_PIE => DisposalModule::t('modules/disposal/app', "Pie"),
            Statistic::CHARTTYPE_POLARAREA => DisposalModule::t('modules/schooltransport/app', "Polar Area")
        ];
    }
}

