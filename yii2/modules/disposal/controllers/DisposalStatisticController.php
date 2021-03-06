<?php
namespace app\modules\disposal\controllers;

use Exception;
use Yii;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use app\modules\base\components\DateHelper;
use app\modules\disposal\DisposalModule;
use app\modules\disposal\models\DisposalStatistic;
use app\modules\eduinventory\components\EduinventoryHelper;
use app\modules\schooltransport\models\Directorate;
use app\modules\disposal\models\Disposal;

class DisposalStatisticController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [   'class' => AccessControl::className(),
                'rules' =>  [
                    ['actions' => ['index', 'exportstatistic', 'exportexcel'], 'allow' => true, 'roles' => ['disposal_viewer']],
                ]
            ]
        ];
    }


    public function beforeAction($action)
    {
        if ($action->id == 'exportstatistic') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }


    public function actionIndex()
    {
        $school_years = DisposalStatistic::getSchoolYearOptions();
        if (is_null($school_years)) {
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', "The are no disposals to show statistics."));
            return $this->redirect(['disposal/index']);
        }

        $prefectures['ALL'] = DisposalModule::t('modules/disposal/app', 'Όλοι οι νομοί');
        $prefectures = $prefectures + EduinventoryHelper::getPrefectures();
        $education_levels['ALL'] = DisposalModule::t('modules/disposal/app', 'Όλες οι βαθμίδες');
        $education_levels = $education_levels + EduinventoryHelper::getEducationalLevels();
        $duties['ALL'] = DisposalModule::t('modules/disposal/app', 'Όλα τα καθήκοντα');
        $duties = $duties + DisposalStatistic::getDutyOptions();
        $reasons['ALL'] = DisposalModule::t('modules/disposal/app', 'Όλοι οι λόγοι');
        $reasons = $reasons + DisposalStatistic::getReasonOptions();
        $specializations['ALL'] = DisposalModule::t('modules/disposal/app', 'Όλες οι ειδικότητες');
        $specializations = $specializations + EduinventoryHelper::getSpecializations();
        $groupby_options = DisposalStatistic::getGroupByOptions();

        $chart_types = DisposalStatistic::getChartTypeOptions();

        $model = new DisposalStatistic();
        $model->statistic_schoolyear = [EduinventoryHelper::getSchoolYearOf(date('Y-m-d'))];
        $model->statistic_educationlevel = 'ALL';
        $model->statistic_prefecture = 'ALL';
        $model->statistic_specialization = 'ALL';
        $model->statistic_duty = 'ALL';
        $model->statistic_reason = 'ALL';
        $model->statistic_groupby = DisposalStatistic::GROUPBY_PERFECTURE;
        $model->statistic_charttype = DisposalStatistic::CHARTTYPE_BAR;
        $result_data = $model->getStatistics();
        //$result_data['LABELS'] = '';
        if (count($result_data['LABELS']) < 2) {
            $model->statistic_charttype = DisposalStatistic::CHARTTYPE_DOUGHNUT;
        }

        if ($model->load(Yii::$app->request->post())) {
            //echo "<pre>"; print_r($model->getStatistics()); echo "</pre>"; die();
            //$model->getStatistics(); die();
            return $this->render('index', ['model' => $model, 'school_years' => $school_years,
                'prefectures' => $prefectures, 'education_levels' => $education_levels, 'chart_types' => $chart_types,
                'duties' => $duties, 'reasons' => $reasons, 'specializations' => $specializations,
                'groupby_options' => DisposalStatistic::getGroupByOptions(),
                'selected_chart_type' => $model->statistic_charttype,
                'chart_title' => $model->getStatisticLiteral(), 'result_data' => $model->getStatistics()
            ]);
        } else {
            return $this->render('index', ['model' => $model, 'school_years' => $school_years,
                'prefectures' => $prefectures, 'education_levels' => $education_levels, 'chart_types' => $chart_types,
                'duties' => $duties, 'reasons' => $reasons, 'specializations' => $specializations,
                'groupby_options' => DisposalStatistic::getGroupByOptions(),
                'selected_chart_type' => $model->statistic_charttype,
                'chart_title' => $model->getStatisticLiteral(), 'result_data' => $result_data
            ]);
        }
    }

    public function actionExportstatistic()
    {
        if (!isset(Yii::$app->request->post()['image_data']) || !isset(Yii::$app->request->post()['table_data'])) {
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', "Error in creating pdf file."));
            return $this->redirect(['index']);
        }

        $content = $this->renderPartial('export', ['image' => Yii::$app->request->post()['image_data'],
            'tabledata' => Yii::$app->request->post()['table_data']]);

        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            'filename' => 'statistic.pdf',
            'destination' => Pdf::DEST_DOWNLOAD,
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'options' => ['title' => 'Περιφερειακή Διεύθυνση Πρωτοβάθμιας και Δευτεροβάθμιας Εκπαίδευσης Κρήτης'],
        ]);
        return $pdf->render();
    }

    public function actionExportexcel($year)
    {
        if ($year == -1) {
            $this->exportExcel();
        }

        $startdate = $year . '-09-01';
        $enddate = ($year+1) . '-08-31';
        $this->exportExcel($startdate, $enddate);
    }

    public function exportExcel($startdate = null, $enddate = null, $savePathFile = null)
    {
        try {
            if (($startdate != null && $enddate != null) && (!DateHelper::validateDate($startdate, 'Y-m-d') || !DateHelper::validateDate($enddate, 'Y-m-d'))) {
                throw new Exception("An invalid period value was given to export data.");
            }
            
            $disposals = Disposal::getPeriodDisposals($startdate, $enddate);
            $spreadsheet = new Spreadsheet();
            $worksheet = $spreadsheet->getActiveSheet();

            $cellstyle =    ['borders' => ['outline' => [   'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                'color' => ['argb' => 'FFFF0000'],]]];

            $row = 1;
            $column = 1;
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Α/Α', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Σχολικό Έτος', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Επώνυμο', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Όνομα', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Πατρώνυμο', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Μητρώνυμο', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Αριθμός Μητρώου', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Ειδικότητα', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Κλάδος', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Διεύθυνση', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Σχολείο Οργανικής/Υπηρέτησης', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Σχολείο Διάθεσης', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Ώρες Διάθεσης', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Έναρξη Διάθεσης', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Λήξη Διάθεσης', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Λόγος Διάθεσης', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Αντικείμενο Διάθεσης', DataType::TYPE_STRING);
            foreach ($disposals as $disposal) {
                $row++;
                $column = 1;
                $startyear = EduinventoryHelper::getSchoolYearOf($disposal['disposal_startdate']);
                $directorate = Directorate::findOne(['directorate_id' => $disposal['directorate_id']]);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $row-1, DataType::TYPE_NUMERIC);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, (string)$startyear . '-' . (string)($startyear+1), DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $disposal['teacher_surname'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $disposal['teacher_name'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $disposal['teacher_fathername'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $disposal['teacher_mothername'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $disposal['teacher_registrynumber'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $disposal['code'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $disposal['name'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $disposal['directorate_shortname'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $disposal['from_school'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $disposal['to_school'], DataType::TYPE_STRING);
                if ($disposal['disposal_hours'] == -1) {
                    $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'ΟΛΙΚΗ ΔΙΑΘΕΣΗ', DataType::TYPE_STRING);
                } else {
                    $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $disposal['disposal_hours'], DataType::TYPE_STRING);
                }
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, Yii::$app->formatter->asDate($disposal['disposal_startdate'], 'dd-MM-Y'), DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, Yii::$app->formatter->asDate($disposal['disposal_enddate'], 'dd-MM-Y'), DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $disposal['disposalreason_description'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $disposal['disposalworkobj_description'], DataType::TYPE_STRING);

                //$worksheet->setCellValueExplicitByColumnAndRow($column++, $row, Yii::$app->formatter->asDate($transport['transport_startdate'], 'dd-MM-Y'), DataType::TYPE_STRING);
                //$worksheet->setCellValueExplicitByColumnAndRow($column++, $row, Yii::$app->formatter->asDate($transport['transport_enddate'], 'dd-MM-Y'), DataType::TYPE_STRING);
                //$worksheet->setCellValueExplicitByColumnAndRow($column++, $row, DisposalModule::t('modules/disposal/app', $directorate['directorate_stage']), DataType::TYPE_STRING);
                //$worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $directorate['directorate_name'], DataType::TYPE_STRING);
            }
            $writer = new Xls($spreadsheet);

            if ($savePathFile === null) {
                header('Content-type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename="file.xls"');
                $writer->save('php://output');
            } else {
                $writer->save($savePathFile);
            }
        } catch (Exception $exc) {
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', $exc->getMessage()));
            Yii::$app->getResponse()->redirect('index');
        } catch (\PhpOffice\PhpSpreadsheet\Exception $phpSprExc) {
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', 'The export folder for the Excel file is not valid'));
            Yii::$app->getResponse()->redirect('index');
        }
    }
}
