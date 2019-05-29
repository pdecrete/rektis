<?php
namespace app\controllers;

use Exception;
use Yii;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use app\models\Leave;
use app\models\LeaveStatistic;
use app\modules\eduinventory\components\EduinventoryHelper;
use app\modules\base\components\DateHelper;


class LeaveStatisticController extends Controller
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
                    ['actions' => ['index', 'exportstatistic', 'exportexcel'], 'allow' => true, 'roles' => ['leave_user']],
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
        $years = LeaveStatistic::getYearOptions();

        if (is_null($years)) {
            Yii::$app->session->addFlash('danger', Yii::t('app', "The are no leaves to show statistics."));
            return $this->redirect(['leave/index']);
        }

        $leavetypes['ALL'] = Yii::t('app', 'Όλοι οι τύποι Αδειών');
        $leavetypes = $leavetypes + LeaveStatistic::getLeaveTypeOptions();
        
        $specialisations['ALL'] = Yii::t('app', 'Όλες οι ειδικότητες');
        $specialisations = $specialisations + EduinventoryHelper::getSpecializations();
        
        $positiontitles['ALL'] = Yii::t('app', 'Όλες οι θέσεις');
        $positiontitles = $positiontitles + LeaveStatistic::getPositionTitlesOptions();
        
        $positionunits['ALL'] = Yii::t('app', 'Όλες οι υπηρεσίες');
        $positionunits = $positionunits + LeaveStatistic::getPositionUnitsOptions();
        
        $employees['ALL'] = Yii::t('app', 'Όλοι οι εργαζόμενοι');
        $employees = $employees + LeaveStatistic::getEmployeeOptions();        
        
        $groupby_options = LeaveStatistic::getGroupByOptions();
        $chart_types = LeaveStatistic::getChartTypeOptions();

        $model = new LeaveStatistic();
        $model->statistic_year = [max(LeaveStatistic::getYearOptions())];
        $model->statistic_leavetype = 'ALL';
        $model->statistic_specialisation = 'ALL';
        $model->statistic_positiontitle = 'ALL';
        $model->statistic_positionunit = 'ALL';
        $model->statistic_employee = 'ALL';
        $model->statistic_groupby = LeaveStatistic::GROUPBY_LEAVETYPE;        
        $result_data = $model->getStatistics();

        if(empty($result_data)) {
            $result_data['LABELS'][0] = 'Ημέρες Άδειας';
            $result_data['LEAVES_COUNT'][0] = 0;
        }
        
        if (count($result_data['LABELS']) <= 2) {
            $model->statistic_charttype = LeaveStatistic::CHARTTYPE_DOUGHNUT;
        }
        else {
            $model->statistic_charttype = LeaveStatistic::CHARTTYPE_BAR;
        }
        

        if ($model->load(Yii::$app->request->post())) {
            $result_data  = $model->getStatistics();
            
            if(empty($result_data)) {
                $result_data['LABELS'][0] = 'Ημέρες Άδειας';
                $result_data['LEAVES_COUNT'][0] = 0;
            }
            return $this->render('index', ['model' => $model, 'years' => $years,
                'leavetypes' => $leavetypes, 'specialisations' => $specialisations, 'positiontitles' => $positiontitles,
                'positionunits' => $positionunits, 'employees' => $employees, 'groupby_options' => LeaveStatistic::getGroupByOptions(),
                'chart_types' => $chart_types, 'selected_chart_type' => $model->statistic_charttype,
                'chart_title' => $model->getStatisticDescription(), 'result_data' => $result_data
            ]);
        } else {
            return $this->render('index', ['model' => $model, 'years' => $years,
                'leavetypes' => $leavetypes, 'specialisations' => $specialisations, 'positiontitles' => $positiontitles,
                'positionunits' => $positionunits, 'employees' => $employees, 'groupby_options' => LeaveStatistic::getGroupByOptions(),
                'chart_types' => $chart_types, 'selected_chart_type' => $model->statistic_charttype,
                'chart_title' => $model->getStatisticDescription(), 'result_data' => $result_data
            ]);
        }
    }

    public function actionExportstatistic()
    {
        if (!isset(Yii::$app->request->post()['image_data']) || !isset(Yii::$app->request->post()['table_data'])) {
            Yii::$app->session->addFlash('danger', Yii::t('app', "Error in creating pdf file."));
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
        if($year == -1) {
            $this->exportExcel();
        }
        
        $startdate = $year . '-01-01';
        $enddate = $year . '-12-31';
        $this->exportExcel($startdate, $enddate);
    }
    
    
    public static function exportExcel($startdate = null, $enddate = null, $savePathFile = null)
    {
        try {            
            if(($startdate != null && $enddate != null) && (!DateHelper::validateDate($startdate, 'Y-m-d') || !DateHelper::validateDate($enddate, 'Y-m-d'))) {
                throw new Exception("Invalid dates");
            }
            
            if($startdate != null && $enddate != null)            
                $leaves = Leave::find()->where(['deleted' => 0])->andWhere(['>=', 'start_date', $startdate])->andWhere(['<=', 'start_date', $enddate])->orderBy('start_date')->all();
            else if($startdate != null)
                $leaves = Leave::find()->where(['deleted' => 0])->andWhere(['>=', 'start_date', $startdate])->orderBy('start_date')->all();
            else if($enddate != null)
                $leaves = Leave::find()->where(['deleted' => 0])->andWhere(['<=', 'start_date', $enddate])->orderBy('start_date')->all();
            else
                $leaves = Leave::find()->where(['deleted' => 0])->orderBy('start_date')->all();

            $spreadsheet = new Spreadsheet();
            $worksheet = $spreadsheet->getActiveSheet();

            $cellstyle =    ['borders' => ['outline' => [   'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                'color' => ['argb' => 'FFFF0000'],]]];

            $row = 1;
            $column = 1;
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Α/Α', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Έτος', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Επώνυμο', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Όνομα', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Πατρώνυμο', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Μητρώνυμο', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Αριθμός Μητρώου', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Ειδικότητα', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Κλάδος', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Τύπος Άδειας', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Έναρξη Άδειας', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Λήξη Άδειας', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Διάρκεια Άδειας', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Λόγος Άδειας', DataType::TYPE_STRING);
            
            foreach ($leaves as $leave) {
                $row++;
                $column = 1;
                $leaveType = $leave->getTypeObj()->one();
                $employee = $leave->getEmployeeObj()->one();
                $specialisation = $employee->getSpecialisation0()->one();
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $row-1, DataType::TYPE_NUMERIC);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, date('Y',strtotime($leave['start_date'])), DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $employee['surname'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $employee['name'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $employee['fathersname'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $employee['mothersname'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $employee['identification_number'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $specialisation['code'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $specialisation['name'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $leaveType['name'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, Yii::$app->formatter->asDate($leave['start_date'], 'dd-MM-Y'), DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, Yii::$app->formatter->asDate($leave['end_date'], 'dd-MM-Y'), DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $leave['duration'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $leave['reason'], DataType::TYPE_STRING);
            }
            $writer = new Xls($spreadsheet);
            if($savePathFile === null) {
                header('Content-type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename="file.xls"');
                $writer->save('php://output');
            }
            else
                $writer->save($savePathFile);
        } catch (Exception $exc) {
            Yii::$app->session->addFlash('danger', Yii::t('app', $exc->getMessage()));
            Yii::$app->getResponse()->redirect('index');
        } catch (\PhpOffice\PhpSpreadsheet\Exception $phpSprExc) {
            Yii::$app->session->addFlash('danger', Yii::t('app', 'The export folder for the Excel file is not valid'));
            Yii::$app->getResponse()->redirect('index');
        }
    }
}
