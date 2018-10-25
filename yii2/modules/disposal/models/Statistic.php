<?php
namespace app\modules\disposal\models;

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
}

