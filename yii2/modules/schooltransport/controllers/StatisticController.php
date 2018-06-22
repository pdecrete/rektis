<?php

namespace app\modules\schooltransport\controllers;

use DateTime;
use Yii;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\modules\schooltransport\Module;
use app\modules\schooltransport\models\Statistic;

class StatisticController extends \yii\web\Controller
{
    /**
     * @inheritdoc
     */
    
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
                    ['actions' => ['index', 'exportstatistic'], 'allow' => true, 'roles' => ['schtransport_viewer']],
                ]
            ]
        ];
    }
    
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    
    public function actionIndex()
    {   
        $school_years = Statistic::getSchoolYearOptions();
        
        $countries['ALL'] = Module::t('modules/schooltransport/app', 'Όλες οι χώρες');
        $countries = array_merge($countries, Statistic::getCountryOptions());
        
        $prefectures['ALL'] = Module::t('modules/schooltransport/app', 'Όλοι οι νομοί');
        $prefectures = array_merge($prefectures, Statistic::getPrefectureOptions());        
        
        $education_levels['ALL'] = Module::t('modules/schooltransport/app', 'Όλες οι βαθμίδες');
        $education_levels = array_merge($education_levels, Statistic::getEducationalLevelOptions());
        
        $program_categs['ALL'] = Module::t('modules/schooltransport/app', 'Όλα τα προγράμματα');
        $program_categs = array_merge($program_categs, Statistic::getProgramCategoryOptions());
        
        $chart_types = Statistic::getChartTypeOptions();
        
        $model = new Statistic();
        $model->statistic_schoolyear = [Statistic::getSchoolYearOf(DateTime::createFromFormat('Y-m-d', date('Y-m-d')))];
        $model->statistic_country = 'ALL';
        $model->statistic_educationlevel = 'ALL';
        $model->statistic_prefecture = 'ALL';
        $model->statistic_program = 'ALL';
        $model->statistic_groupby = Statistic::GROUPBY_PERFECTURE;
        $model->statistic_charttype = Statistic::CHARTTYPE_BAR;
        $result_data = $model->getStatistics();

        if(count($result_data['LABELS']) < 2)
            $model->statistic_charttype = Statistic::CHARTTYPE_DOUGHNUT;

        if ($model->load(Yii::$app->request->post())){
            return $this->render('index', ['model' => $model, 'school_years' => $school_years, 'countries' => $countries,
                                           'prefectures' => $prefectures, 'education_levels' => $education_levels,
                                           'groupby_options' => Statistic::getGroupByOptions(), 'program_categs' => $program_categs,
                                           'chart_types' => $chart_types, 'selected_chart_type' => $model->statistic_charttype, 
                                           'chart_title' => $model->getStatisticLiteral(), 'result_data' => $model->getStatistics()
            ]);
            
        }
        else { 
            return $this->render('index', ['model' => $model, 'school_years' => $school_years, 'countries' => $countries, 
                                 'prefectures' => $prefectures, 'education_levels' => $education_levels,
                                 'groupby_options' => Statistic::getGroupByOptions(), 'program_categs' => $program_categs,
                                 'chart_types' => $chart_types, 'selected_chart_type' => $model->statistic_charttype, 
                                 'chart_title' => $model->getStatisticLiteral(), 'result_data' => $result_data]);
        }
    }
    
    
    public function actionExportstatistic(){
        if(!isset(Yii::$app->request->post()['image_data']) || !isset(Yii::$app->request->post()['table_data'])){
            Yii::$app->session->addFlash('danger', Module::t('modules/schooltransport/app', "Error in creating pdf file."));
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

}

