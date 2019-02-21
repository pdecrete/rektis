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
use app\models\TransportStatistic;
use app\modules\eduinventory\components\EduinventoryHelper;
use app\models\Transport;


class TransportStatisticController extends Controller
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
                    ['actions' => ['index', 'exportstatistic', 'exportexcel'], 'allow' => true, 'roles' => ['transport_user']],
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
        $years = TransportStatistic::getYearOptions();

        if (is_null($years)) {
            Yii::$app->session->addFlash('danger', Yii::t('app', "There are no transports to show statistics."));
            return $this->redirect(['transport/index']);
        }

        $tranposrtvehicles['ALL'] = Yii::t('app', 'Όλα τα μέσα μετακίνησης');
        $tranposrtvehicles = $tranposrtvehicles + TransportStatistic::getTransportVehicleOptions();
        
        $expendituretypes['ALL'] = Yii::t('app', 'Όλοι οι τύποι δαπάνης');
        $expendituretypes = $expendituretypes + TransportStatistic::getTransportExpenditureTypeOptions();
        
        $specialisations['ALL'] = Yii::t('app', 'Όλες οι ειδικότητες');
        $specialisations = $specialisations + EduinventoryHelper::getSpecializations();
                
        $positionunits['ALL'] = Yii::t('app', 'Όλες οι υπηρεσίες');
        $positionunits = $positionunits + TransportStatistic::getPositionUnitsOptions();
        
        $employees['ALL'] = Yii::t('app', 'Όλοι οι εργαζόμενοι');
        $employees = $employees + TransportStatistic::getEmployeeOptions();
        
        $days_applied['ALL'] = Yii::t('app', 'Οποιοσδήποτε αριθμός');
        $days_applied = $days_applied + TransportStatistic::getDaysOptions();
        
        $days_out['ALL'] = Yii::t('app', 'Οποιοσδήποτε αριθμός');
        $days_out = $days_out + TransportStatistic::getDaysOutOptions();

        $nights_out['ALL'] = Yii::t('app', 'Οποιοσδήποτε αριθμός');
        $nights_out = $nights_out + TransportStatistic::getNightsOutOptions();
        
        $groupby_options = TransportStatistic::getGroupByOptions();
        $chart_types = TransportStatistic::getChartTypeOptions();

        $model = new TransportStatistic();
        $model->statistic_year = [max(TransportStatistic::getYearOptions())];
        $model->statistic_vehicle = 'ALL';
        $model->statistic_expendituretype = 'ALL';
        $model->statistic_days = 'ALL';
        $model->statistic_daysout = 'ALL';
        $model->statistic_nightsout = 'ALL';
        $model->statistic_specialisation = 'ALL';
        $model->statistic_positionunit = 'ALL';
        $model->statistic_employee = 'ALL';
        $model->statistic_groupby = TransportStatistic::GROUPBY_EXPENDITURETYPE;        
        $result_data = $model->getStatistics();

        if(empty($result_data)) {
            $result_data['LABELS'][0] = 'Ημέρες Άδειας';
            $result_data['TRANSPORTS_COUNT'][0] = 0;
        }
        
        if (count($result_data['LABELS']) <= 2) {
            $model->statistic_charttype = TransportStatistic::CHARTTYPE_DOUGHNUT;
        }
        else {
            $model->statistic_charttype = TransportStatistic::CHARTTYPE_BAR;
        }
        

        if ($model->load(Yii::$app->request->post())) {
            $result_data  = $model->getStatistics();
            
            if(empty($result_data)) {
                $result_data['LABELS'][0] = 'Ημέρες Άδειας';
                $result_data['TRANSPORTS_COUNT'][0] = 0;
            }
            return $this->render('index', ['model' => $model, 'years' => $years,
                'expendituretypes' => $expendituretypes,'tranposrtvehicles' =>$tranposrtvehicles, 'specialisations' => $specialisations,
                'days' => $days_applied, 'daysout' => $days_out, 'nightsout' => $nights_out,
                'positionunits' => $positionunits, 'employees' => $employees, 'groupby_options' => TransportStatistic::getGroupByOptions(),
                'chart_types' => $chart_types, 'selected_chart_type' => $model->statistic_charttype,
                'chart_title' => $model->getStatisticDescription(), 'result_data' => $result_data
            ]);
        } else {
            return $this->render('index', ['model' => $model, 'years' => $years,
                'expendituretypes' => $expendituretypes, 'tranposrtvehicles' =>$tranposrtvehicles, 'specialisations' => $specialisations,
                'days' => $days_applied, 'daysout' => $days_out, 'nightsout' => $nights_out,
                'positionunits' => $positionunits, 'employees' => $employees, 'groupby_options' => TransportStatistic::getGroupByOptions(),
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
        try {
            if (!isset($year) || is_null($year) || !is_numeric($year)) {
                throw new Exception("An invalid period value was given to export school transports data.");
            }
            
            $start_date = $year . '-01-01';
            $end_date = $year . '-12-31';
            
            if($year != -1)
                $transports = Transport::find()->where(['deleted' => 0])->andWhere(['>', 'start_date', $start_date])->andWhere(['<', 'start_date', $end_date])->all();
            else
                $transports = Transport::find()->where(['deleted' => 0])->all();

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
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Δαπάνη Μετακίνησης', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Έναρξη Μετακίνησης', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Λήξη Μετακίνησης', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Αριθμός Ημερών Μετακίνησης', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Αριθμός Εκτός Έδρας Ημερών Μετακίνησης', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Αριθμός Διανυκτερεύσεων Μετακίνησης', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Λόγος Μετακίνησης', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Δρομολόγιο Μετακίνησης', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Απόσταση Μετακίνησης (χλμ)', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Μέσο Μετακίνησης', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Χιλιομετρική Αποζημίωση', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Αντίτιμο Εισιτηρίου', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Ημερήσια Αποζημίωση', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Αποζημίωση Διανυκτέρευσης', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Συνολικό Κόστος Μετακίνησης', DataType::TYPE_STRING);
            $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, 'Πληρωτέο Πόσο', DataType::TYPE_STRING);
            
            foreach ($transports as $transport) {
                $row++;
                $column = 1;                
                $employee = $transport->getEmployee0()->one();
                $specialisation = $employee->getSpecialisation0()->one();
                $transp_expnd_type = $transport->getType0()->one();
                $vehicle = $transport->getMode0()->one();
                $route = $transport->getFromTo()->one();
                
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $row-1, DataType::TYPE_NUMERIC);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, date('Y',strtotime($transport['start_date'])), DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $employee['surname'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $employee['name'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $employee['fathersname'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $employee['mothersname'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $employee['identification_number'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $specialisation['code'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $specialisation['name'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $transp_expnd_type['name'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, Yii::$app->formatter->asDate($transport['start_date'], 'dd-MM-Y'), DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, Yii::$app->formatter->asDate($transport['end_date'], 'dd-MM-Y'), DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $transport['days_applied'], DataType::TYPE_NUMERIC);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $transport['days_out'], DataType::TYPE_NUMERIC);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $transport['nights_out'], DataType::TYPE_NUMERIC);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $transport['reason'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $route['name'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $transport['klm'], DataType::TYPE_NUMERIC);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $vehicle['name'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $transport['klm_reimb'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $transport['ticket_value'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $transport['day_reimb'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $transport['night_reimb'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $transport['reimbursement'], DataType::TYPE_STRING);
                $worksheet->setCellValueExplicitByColumnAndRow($column++, $row, $transport['pay_amount'], DataType::TYPE_STRING);
                
            }
            $writer = new Xls($spreadsheet);
            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="file.xls"');
            $writer->save('php://output');
        } catch (Exception $exc) {
            Yii::$app->session->addFlash('danger', Yii::t('app', $exc->getMessage()));
            return $this->redirect('index');
        }
    }
}
