<?php

namespace app\commands;

use Yii;
use app\controllers\LeaveStatisticController;
use app\modules\base\components\DateHelper;
use app\controllers\TransportStatisticController;
use app\modules\schooltransport\controllers\StatisticController;
use app\modules\disposal\controllers\DisposalStatisticController;

class ReportingController extends \yii\console\Controller
{
    const NONE = 0;
    const WEEKLY = 1;
    const MONTHLY = 2;
    //const YEARLY = 3;

    public $disposalsfreq = ReportingController::MONTHLY;
    public $leavesfreq = ReportingController::MONTHLY;
    public $transportsfreq = ReportingController::MONTHLY;
    public $schtransportsfreq = ReportingController::MONTHLY;

    public function options($actionID)
    {
        return ['disposalsfreq', 'leavesfreq', 'transportsfreq', 'schtransportsfreq'];
    }

    public function optionAliases()
    {
        return ['d' => 'disposalsfreq', 'l' => 'leavesfreq', 't' => 'transportsfreq', 'st' => 'schtransportsfreq'];
    }



    public function actionIndex()
    {
        $config = require(__DIR__ . '/../config/web.php');
        $months = ['Ιανουάριο', 'Φεβρουάριο', 'Μάρτιο', 'Απρίλιο', 'Μάιο', 'Ιούνιο', 'Ιούλιο', 'Αύγουστο', 'Σεπτέμβριο', 'Οκτώβριο', 'Νοέμβριο', 'Δεκέμβριο'];

        Yii::$app->mailer->setTransport($config['components']['mailer']['transport']);
        $time = time();
        $subject_prefix = '[ΤΜΗΜΑ Δ΄/ΠΛΗΡΟΦΟΡΙΚΗΣ - ΑΥΤΟΜΑΤΟΠΟΙΗΜΕΝΟ ΜΗΝΥΜΑ] ';
        $bodytext = "";

        /* Disposals reporting */
        $today = date_create(date("Y-m-d", $time));
        if ($this->disposalsfreq !== ReportingController::NONE) {
            if ($this->disposalsfreq == ReportingController::MONTHLY) {
                $exportdate = date_sub($today, new \DateInterval('P1M'));
                $month = date_format($exportdate, "m");
                $year = date_format($exportdate, "Y");
                $subject = $subject_prefix . 'Ενημερωτικό εγκεκριμένων διαθέσεων ' . $months[intval($month)-1] . 'ς ' . $year;
                $dataexport_startdate = date_format($exportdate, "Y-m") . '-' . '01';
                $dataexport_enddate = date_format($exportdate, "Y-m") . '-' . cal_days_in_month(CAL_GREGORIAN, $month, $year);
                $export_file = Yii::getAlias($params['reporting_exportfolder'] . 'ΔΙΑΘΕΣΕΙΣ_M' .  $month . 'Y' . $year . '.xls');
                DisposalStatisticController::exportExcel($dataexport_startdate, $dataexport_enddate, $export_file);
            } elseif ($this->disposalsfreq == ReportingController::WEEKLY) {
                $exportdate = date_sub($today, new \DateInterval('P1W'));
                $week = date_format($exportdate, "W");
                $year = date_format($exportdate, "Y");
                $week_dates = DateHelper::getStartAndEndDate($week, $year);
                $subject = $subject_prefix . 'Ενημερωτικό εγκεκριμένων διαθέσεων εβδομάδας ' . date("d-m-Y", strtotime($week_dates['week_start'])) . ' έως ' . date("d-m-Y", strtotime($week_dates['week_end']));
                $export_file = Yii::getAlias($params['reporting_exportfolder'] . 'ΔΙΑΘΕΣΕΙΣ_W' .  $week .  'Y' . $year . '.xls');
                DisposalStatisticController::exportExcel($week_dates['week_start'], $week_dates['week_end'], $export_file);
            }

            Yii::$app->mailer->compose()
            ->setFrom('noreply@pdekritis.gr')
            ->setTo($params['reportingTo_disposals'])
            ->setSubject($subject)
            ->setTextBody($bodytext)
            ->attach($export_file)
            ->send();
        }

        /* Leaves reporting */
        $today = date_create(date("Y-m-d", $time));
        if ($this->leavesfreq !== ReportingController::NONE) {
            if ($this->leavesfreq == ReportingController::MONTHLY) {
                $exportdate = date_sub($today, new \DateInterval('P1M'));
                $month = date_format($exportdate, "m");
                $year = date_format($exportdate, "Y");
                $subject = $subject_prefix . 'Ενημερωτικό αδειών ' . $months[intval($month)-1] . 'ς ' . $year;
                $dataexport_startdate = date_format($exportdate, "Y-m") . '-' . '01';
                $dataexport_enddate = date_format($exportdate, "Y-m") . '-' . cal_days_in_month(CAL_GREGORIAN, $month, $year);
                $export_file = Yii::getAlias($params['reporting_exportfolder'] . 'ΑΔΕΙΕΣ_M' .  $month . 'Y' . $year . '.xls');
                LeaveStatisticController::exportExcel($dataexport_startdate, $dataexport_enddate, $export_file);
            } elseif ($this->leavesfreq == ReportingController::WEEKLY) { 
                $exportdate = date_sub($today, new \DateInterval('P1W'));
                $week = date_format($exportdate, "W");
                $year = date_format($exportdate, "Y");
                $week_dates = DateHelper::getStartAndEndDate($week, $year);
                $subject = $subject_prefix . 'Ενημερωτικό αδειών εβδομάδας ' . date("d-m-Y", strtotime($week_dates['week_start'])) . ' έως ' . date("d-m-Y", strtotime($week_dates['week_end']));
                $export_file = Yii::getAlias($params['reporting_exportfolder'] . 'ΑΔΕΙΕΣ_W' .  $week .  'Y' . $year . '.xls');
                LeaveStatisticController::exportExcel($week_dates['week_start'], $week_dates['week_end'], $export_file);
            }

            Yii::$app->mailer->compose()
            ->setFrom('noreply@pdekritis.gr')
            ->setTo($params['reportingTo_leaves'])
            ->setSubject($subject)
            ->setTextBody($bodytext)
            ->attach($export_file)
            ->send();
        }

        /* Transports reporting */
        $today = date_create(date("Y-m-d", $time));
        if ($this->transportsfreq !== ReportingController::NONE) {
            if ($this->transportsfreq == ReportingController::MONTHLY) {
                $exportdate = date_sub($today, new \DateInterval('P1M'));
                $month = date_format($exportdate, "m");
                $year = date_format($exportdate, "Y");
                $subject = $subject_prefix . 'Ενημερωτικό μετακινήσεων εργαζομένων ' . $months[intval($month)-1] . 'ς ' . $year;
                $dataexport_startdate = date_format($exportdate, "Y-m") . '-' . '01';
                $dataexport_enddate = date_format($exportdate, "Y-m") . '-' . cal_days_in_month(CAL_GREGORIAN, $month, $year);
                $export_file = Yii::getAlias($params['reporting_exportfolder'] . 'ΜΕΤΑΚΙΝΗΣΕΙΣ_ΕΡΓΑΖΟΜΕΝΩΝ_M' .  $month . 'Y' . $year . '.xls');
                TransportStatisticController::exportExcel($dataexport_startdate, $dataexport_enddate, $export_file);
            } elseif ($this->transportsfreq == ReportingController::WEEKLY) {
                $exportdate = date_sub($today, new \DateInterval('P1W'));
                $week = date_format($exportdate, "W");
                $year = date_format($exportdate, "Y");
                $week_dates = DateHelper::getStartAndEndDate($week, $year);
                $subject = $subject_prefix . 'Ενημερωτικό μετακινήσεων εργαζομένων ' . date("d-m-Y", strtotime($week_dates['week_start'])) . ' έως ' . date("d-m-Y", strtotime($week_dates['week_end']));
                $export_file = Yii::getAlias($params['reporting_exportfolder'] . 'ΜΕΤΑΚΙΝΗΣΕΙΣ_ΕΡΓΑΖΟΜΕΝΩΝ_W' .  $week .  'Y' . $year . '.xls');
                TransportStatisticController::exportExcel($week_dates['week_start'], $week_dates['week_end'], $export_file);
            }

            Yii::$app->mailer->compose()
            ->setFrom('noreply@pdekritis.gr')
            ->setTo($params['reportingTo_transports'])
            ->setSubject($subject)
            ->setTextBody($bodytext)
            ->attach($export_file)
            ->send();
        }

        /* School Transports reporting */
        $today = date_create(date("Y-m-d", $time));
        if ($this->schtransportsfreq !== ReportingController::NONE) {
            if ($this->schtransportsfreq == ReportingController::MONTHLY) {
                $exportdate = date_sub($today, new \DateInterval('P1M'));
                $month = date_format($exportdate, "m");
                $year = date_format($exportdate, "Y");
                $subject = $subject_prefix . 'Ενημερωτικό σχολικών μετακινήσεων ' . $months[intval($month)-1] . 'ς ' . $year;
                $dataexport_startdate = date_format($exportdate, "Y-m") . '-' . '01';
                $dataexport_enddate = date_format($exportdate, "Y-m") . '-' . cal_days_in_month(CAL_GREGORIAN, $month, $year);
                $export_file = Yii::getAlias($params['reporting_exportfolder'] . 'ΣΧΟΛΙΚΕΣ_ΜΕΤΑΚΙΝΗΣΕΙΣ_M' .  $month . 'Y' . $year . '.xls');
                StatisticController::exportExcel($dataexport_startdate, $dataexport_enddate, $export_file);
            } elseif ($this->schtransportsfreq == ReportingController::WEEKLY) {
                $exportdate = date_sub($today, new \DateInterval('P1W'));
                $week = date_format($exportdate, "W");
                $year = date_format($exportdate, "Y");
                $week_dates = DateHelper::getStartAndEndDate($week, $year);
                $subject = $subject_prefix . 'Ενημερωτικό σχολικών μετακινήσεων ' . date("d-m-Y", strtotime($week_dates['week_start'])) . ' έως ' . date("d-m-Y", strtotime($week_dates['week_end']));
                $export_file = Yii::getAlias($params['reporting_exportfolder'] . 'ΣΧΟΛΙΚΕΣ_ΜΕΤΑΚΙΝΗΣΕΙΣ_W' .  $week .  'Y' . $year . '.xls');
                StatisticController::exportExcel($week_dates['week_start'], $week_dates['week_end'], $export_file);
            }

            Yii::$app->mailer->compose()
            ->setFrom('noreply@pdekritis.gr')
            ->setTo($params['reportingTo_schtransports'])
            ->setSubject($subject)
            ->setTextBody($bodytext)
            ->attach($export_file)
            ->send();
        }
    }

    public function actionHelp()
    {
        echo "***\nReporting command can be used to schedule send of reporting for the various decision categories created within the organization.\n";
        echo "Specifically it supports decisions for the leaves, the transports of the employees, the school transports and the disposals of teachers.\n";
        echo "\nThe command sends to the e-mails declared in the params.php file a reporting in Excel files form of the decisions created during\nthe previous week or month (based on the current date when command is executed).\n";
        echo "For each kind of decisions, weekly, monthly or none reporting is supported.\n";
        echo "\nThe default frequency of reporting is monthly for each kind of decisions. To change please refer to the flags below.\n";
        echo "\nUsage:\n";
        echo "\tyii reporting/index\n";
        echo "\n\t-d\tFor scheduling disposals reporting\n\t\t(-d=2 for MONTHLY, -d=1 for WEEKLY, -d=0 for NONE)\n";
        echo "\n\t-l\tFor scheduling leaves reporting\n\t\t(-l=2 for MONTHLY, -l=1 for WEEKLY, -l=0 for NONE)\n";
        echo "\n\t-t\tFor scheduling employees' transports reporting\n\t\t(-t=2 for MONTHLY, -t=1 for WEEKLY, -t=0 for NONE)\n";
        echo "\n\t-st\tFor scheduling school transports reporting\n\t\t(-st=2 for MONTHLY, -st=1 for WEEKLY, -st=0 for NONE)\n";
        echo "\nExamples:\n";
        echo "\t[1] yii reporting/index -d=0 -l=2 -t=1 -st=1\n";
        echo "\t    (Sends no reporting for the disposals (since -d=0), monthly reporting for the leaves, weekly reporting for the employees transports and school transports.)\n";
        echo "\n\t[2] yii reporting/index";
        echo "\n\t    (Default frequency is considered. Sends monthly reporting for all kinds of decisions.)\n***\n"; 
    }
}
