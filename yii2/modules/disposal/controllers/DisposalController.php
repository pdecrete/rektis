<?php

namespace app\modules\disposal\controllers;

use DateTime;
use Yii;
use app\modules\disposal\models\Disposal;
use app\modules\disposal\models\DisposalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\base\Exception;
use yii\filters\VerbFilter;
use app\modules\disposal\DisposalModule;
use app\models\Specialisation;
use app\models\Teacher;
use app\modules\schooltransport\models\Schoolunit;
use app\modules\schooltransport\models\Statistic;
use app\modules\disposal\models\DisposalLedger;
use Defuse\Crypto\Exception\EnvironmentIsBrokenException;
use app\modules\disposal\models\DisposalReason;
use app\modules\disposal\models\DisposalWorkobj;
use yii\helpers\Json;
use app\modules\disposal\models\DisposalLocaldirdecision;
use app\modules\schooltransport\models\Directorate;
use app\modules\disposal\models\DisposalImport;
use PhpOffice\PhpSpreadsheet\Worksheet\RowIterator;

/**
 * DisposalController implements the CRUD actions for Disposal model.
 */
class DisposalController extends Controller
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
        ];
    }

    
    public function actionGetlocaldirdecisionAjax()
    {
        $data = null;
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $data = Yii::$app->request->post('localdirdecision_protocol');
            $localdir_decision = DisposalLocaldirdecision::findOne(['localdirdecision_protocol' => $data]);
            $data = $localdir_decision;
        }
        
        return Json::encode($data);
    }
    
    public function actionGetteacherAjax()
    {
        $data = null;
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $data = Yii::$app->request->post('regNumber');
            $teacher = Teacher::findOne(['teacher_registrynumber' => intval($data)]);
            $data = $teacher;
            //echo "<pre>"; print_r($teacher); echo "</pre>"; die();
        }
        
        return Json::encode($data);
    }
    
    /**
     * Lists all Disposal models.
     * @return mixed
     */
    public function actionIndex($archived = 0)
    {   
        if (!is_numeric($archived) || ($archived != 0 && $archived != 1)) {
            $archived = 0;
        }
        
        $disposal_reasons = DisposalReason::find()->all();
        $specialisations = Specialisation::find()->select('code')->all();
        $directorates = Directorate::find()->select('directorate_shortname')->all();
        $decisions_protocols = DisposalLocaldirdecision::find()->select('localdirdecision_protocol')->all();
                
        $searchModel = new DisposalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $archived);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'archived' => $archived,
            'disposal_reasons' => $disposal_reasons,
            'specialisations' => $specialisations,
            'directorates' => $directorates,
            'decisions_protocols' => $decisions_protocols
        ]);
    }

    /**
     * Displays a single Disposal model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $archived = 0)
    {        
        $model = $this->findModel($id);
        $teacher = $model->getTeacher()->one();
        $specialisation = Specialisation::findOne(['id' => $teacher['specialisation_id']]);
        $organicpost = Schoolunit::findOne(['school_id' => $teacher['school_id']]);
        $disposal_school = $model->getSchool()->one();
        $disposal_reason = $model->getDisposalreason()->one();
        $disposal_workobj = $model->getDisposalworkobj()->one();
        $array_model = $model->toArray();
        
        if($array_model['disposal_hours'] == Disposal::FULL_DISPOSAL)
            $array_model['disposal_hours'] = 'Ολική Διάθεση';
        else 
            $array_model['disposal_hours'] .= ' ώρες';
        $array_model['disposal_startdate'] = date_format(date_create($model['disposal_startdate']), 'd/m/Y');
        $array_model['disposal_enddate'] = date_format(date_create($model['disposal_enddate']), 'd/m/Y');
        $array_model['teacher_id'] = $teacher['teacher_surname'] . ' ' . $teacher['teacher_name'] . ' (' . $specialisation['code'] . ', ' . $specialisation['name'] . ')';
        $array_model['school_id'] = $disposal_school['school_name'];
        $array_model['Organic Post'] = $organicpost->school_name;
        $array_model['disposalreason_id'] = $disposal_reason['disposalreason_description'];
        $array_model['disposalworkobj_id'] = $disposal_workobj['disposalworkobj_description'];

        
        
        //echo "<pre>"; print_r($array_model); echo "</pre>"; die();
        
        return $this->render('view', [
            'model' => $array_model,
            'archived' => $archived
        ]);
    }

    /**
     * Creates a new Disposal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        try {
            $transaction = Yii::$app->db->beginTransaction();
            $teacher_model = new Teacher();
            $localdirdecision_model = new DisposalLocaldirdecision();
            $schools = Schoolunit::find()->all();
            $specialisations = Specialisation::find()->all();
            $disposal_reasons = DisposalReason::find()->all();
            $disposal_workobjs = DisposalWorkobj::find()->all();
            $model = new Disposal();           
                       
            $disposal_hours = Disposal::getHourOptions();
            
            if ($model->load(Yii::$app->request->post()) && $teacher_model->load(Yii::$app->request->post()) && $localdirdecision_model->load(Yii::$app->request->post())) {
                                    
                if($model->school_id == $teacher_model->school_id)
                    throw new Exception("The school of the disposal must be different to the school of the organic position of the teacher");
                
                //TODO
                //if($model->getSchool()->one()->directorate_id != $teacher_model->getSchool()->one()->directorate_id)
                //    throw new Exception("The directorate of the organic position of the teacher must be the same to the directorate of the disposal school.");
                
                if($model->disposal_enddate <= $model->disposal_startdate)
                    throw new Exception("The start date of the disposal must be earlier its end date.");

                if($model->disposal_enddate == "")
                        $model->disposal_endofteachingyear_flag = 1;
                
                if($model->disposal_endofteachingyear_flag == 1) {
                    $school_model = Schoolunit::findOne(['school_id' => $model->school_id]);
                    if($school_model->getSchoolStage() == 'PRIMARY'){
                        $timestamp = strtotime($this->module->params['teachyear_enddate_primary'] . '-' .
                                     (Statistic::getSchoolYearOf(DateTime::createFromFormat("Y-m-d", $model->disposal_startdate)) + 1));
                        $model->disposal_enddate = date("Y-m-d", $timestamp);
                    }
                    else if($school_model->getSchoolStage() == 'SECONDARY')
                        $timestamp = strtotime($this->module->params['teachyear_enddate_secondary'] . '-' .
                                     (Statistic::getSchoolYearOf(DateTime::createFromFormat("Y-m-d", $model->disposal_startdate)) + 1));
                        $model->disposal_enddate = date("Y-m-d", $timestamp);
                }
                

                $existing_teacher_model = Teacher::findOne(['teacher_registrynumber' => $teacher_model->teacher_registrynumber]);
                if(is_null($existing_teacher_model)) {
                    if(!$teacher_model->save()) {
                        throw new Exception("Error in saving the teacher details in the database.");
                    }
                    $model->teacher_id = $teacher_model->teacher_id;
                }
                else
                    $model->teacher_id = $existing_teacher_model->teacher_id;
                
                $existing_localdirdecision_model = DisposalLocaldirdecision::findOne(['localdirdecision_protocol' => $localdirdecision_model->localdirdecision_protocol]);
                if(is_null($existing_localdirdecision_model)) {
                    if(!$localdirdecision_model->save()) {
                        throw new Exception("Error in saving the teacher details in the database.");
                    }
                    $model->localdirdecision_id = $localdirdecision_model->localdirdecision_id;
                }
                else
                    $model->localdirdecision_id = $existing_localdirdecision_model->localdirdecision_id;
                
                if(!$model->save()){
                    //echo "<pre>"; print_r($model->errors); echo "<pre>"; die();
                    throw new Exception("Error in saving the disposal details in the database.");
                }
                
                
                /* This should be placed in the code part that finalizes the approval of the disposal by PDE 
                $ledger_model = new DisposalLedger();
                $ledger_model->setAttributes($model->attributes);
                if(!$ledger_model->save()){
                    throw new Exception("Error in completing transaction for disposal save.");
                }*/
                
                $transaction->commit();
                Yii::$app->session->addFlash('success', DisposalModule::t('modules/disposal/app', "The teacher disposal was saved successfully."));
                return $this->redirect(['index']);
            }
            else {
                return $this->render('create', [
                    'model' => $model,
                    'teacher_model' => $teacher_model,
                    'localdirdecision_model' => $localdirdecision_model,
                    'schools' => $schools,
                    'disposal_hours' => $disposal_hours,
                    'specialisations' => $specialisations,
                    'disposal_reasons' => $disposal_reasons,
                    'disposal_workobjs' => $disposal_workobjs
                ]);
            }
        }
        catch (Exception $exc) {            
            $transaction->rollBack();
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', $exc->getMessage()));
            return $this->render('create', [
                'model' => $model,
                'teacher_model' => $teacher_model,
                'localdirdecision_model' => $localdirdecision_model,
                'schools' => $schools,
                'disposal_hours' => $disposal_hours,
                'specialisations' => $specialisations,
                'disposal_reasons' => $disposal_reasons,
                'disposal_workobjs' => $disposal_workobjs
            ]);
        }
    }

    /**
     * Updates an existing Disposal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        try {            
            $model = $this->findModel($id);
            
            if($model->deleted == 1 || $model->archived == 1){
                Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', "The disposal is not allowed to be updated."));
                return $this->redirect(['index']);
            }                
            $localdirdecision_model = DisposalLocaldirdecision::findOne(['localdirdecision_id' => $model->localdirdecision_id]);
            $teacher_model = Teacher::findOne(['teacher_id' => $model->teacher_id]);
            $schools = Schoolunit::find()->all();
            $specialisations = Specialisation::find()->all();
            $disposal_reasons = DisposalReason::find()->all();
            $disposal_workobjs = DisposalWorkobj::find()->all();
            
            $disposal_hours = Disposal::getHourOptions();
                
            if ($model->load(Yii::$app->request->post()) && $teacher_model->load(Yii::$app->request->post())) { // && $localdirdecision_model->load(Yii::$app->request->post())) {
                             
                $transaction = Yii::$app->db->beginTransaction();

                if($model->school_id == $teacher_model->school_id)
                    throw new Exception("The school of the disposal must be different to the school of the organic position of the teacher");
                
                if($model->disposal_enddate <= $model->disposal_startdate)
                    throw new Exception("The start date of the disposal must be earlier its end date.");
                
                if($model->disposal_enddate = "")
                    $model->disposal_endofteachingyear_flag = 1;
                
                if($model->disposal_endofteachingyear_flag == 1) {
                    $school_model = Schoolunit::findOne(['school_id' => $model->school_id]);                        
                    if($school_model->getSchoolStage() == 'PRIMARY'){
                        $timestamp = strtotime($this->module->params['teachyear_enddate_primary'] . '-' . 
                                        (Statistic::getSchoolYearOf(DateTime::createFromFormat("Y-m-d", $model->disposal_startdate)) + 1));
                        $model->disposal_enddate = date("Y-m-d", $timestamp);
                    }
                    else if($school_model->getSchoolStage() == 'SECONDARY')
                        $timestamp = strtotime($this->module->params['teachyear_enddate_secondary'] . '-' .
                                        (Statistic::getSchoolYearOf(DateTime::createFromFormat("Y-m-d", $model->disposal_startdate)) + 1));
                        $model->disposal_enddate = date("Y-m-d", $timestamp);
                }
                    
                if(!$teacher_model->save()) {
                    throw new Exception("Error in saving the teacher details in the database.");
                }
                $model->teacher_id = $teacher_model->teacher_id;
                                
                if(!$model->save()){
                    throw new Exception("Error in saving the disposal details in the database.");
                }
                /* This should be placed in the code part that finalizes the approval of the disposal by PDE
                $ledger_model = new DisposalLedger();
                $ledger_model->setAttributes($model->attributes);
                if(!$ledger_model->save()){
                    throw new Exception("Error in completing transaction for disposal save.");
                }*/
                
                $transaction->commit();
                Yii::$app->session->addFlash('success', DisposalModule::t('modules/disposal/app', "The teacher disposal was saved successfully."));
                return $this->redirect(['index']);
            }
            else {
                return $this->render('update', [
                    'model' => $model,
                    'teacher_model' => $teacher_model,
                    'localdirdecision_model' => $localdirdecision_model,
                    'schools' => $schools,
                    'disposal_hours' => $disposal_hours,
                    'specialisations' => $specialisations,
                    'disposal_reasons' => $disposal_reasons,
                    'disposal_workobjs' => $disposal_workobjs
                ]);
            }
        }
        catch (Exception $exc) {
            $transaction->rollBack();
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', $exc->getMessage()));
            return $this->render('update', [
                'model' => $model,
                'teacher_model' => $teacher_model,
                'localdirdecision_model' => $localdirdecision_model,
                'schools' => $schools,
                'disposal_hours' => $disposal_hours,
                'specialisations' => $specialisations,
                'disposal_reasons' => $disposal_reasons,
                'disposal_workobjs' => $disposal_workobjs
            ]);
        }
    }

    /**
     * Deletes an existing Disposal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            $model = $this->findModel($id);
            $model->deleted = 1;
            if(!$model->save())
                throw new Exception();
            
            Yii::$app->session->addFlash('success', DisposalModule::t('modules/disposal/app', "The teacher disposal was deleted successfully."));
            return $this->redirect(['index']);
        }
        catch (Exception $exc) {
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', "The teacher disposal deletion failed."));
            return $this->redirect(['index']);
        }
    }

    
    public function actionImportdisposals() {
        $cells = ['DIRECTORATE' => 'C3', 'PROTOCOL' => 'C4', 'ACTION' => 'C5', 'SUBJECT' => 'C6'];
        $disposals_columns = [  'AM' => 2, 'SURNAME' => 3, 'NAME' => 4, 'SPECIALISATION' => 5, 'ORGANIC_SCHOOL' => 6, 'DISPOSAL_SCHOOL' => 7, 
                                'HOURS' => 8, 'START_DATE' => 9, 'END_DATE' => 10, 'DISPOSAL_REASON' => 11, 'DISPOSAL_DUTY' => 12];
        $base_disposalsdata_row = 9;
        try {
            $transaction = Yii::$app->db->beginTransaction();
            $import_model = new DisposalImport();
            
            if ($import_model->load(Yii::$app->request->post())) {
                $import_model->excelfile_disposals = \yii\web\UploadedFile::getInstance($import_model, 'excelfile_disposals');
                //$filename = $import_model->excelfile_disposals->tempName . '/' . $import_model->excelfile_disposals;
                //echo $import_model->excelfile_disposals; die();
                //echo $filename; die();
                if(!$import_model->upload()) {
                    echo "<pre>"; print_r($import_model->errors); echo "</pre>"; die(); 
                    throw new Exception("(@upload)");
                }
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(Yii::getAlias(Yii::$app->controller->module->params['disposal_importfolder']) . $import_model->excelfile_disposals);
                if(!$spreadsheet) {
                    echo "<pre>"; print_r($spreadsheet->errors); echo "</pre>"; die();
                    throw new Exception("(@import)");
                }
                
                $disposals_worksheet = $spreadsheet->getSheetByName('Διαθέσεις');
                //if($disposals_worksheet->getCellByColumnAndRow(2, 3) == null) echo "It is null"; die();
                
                $directorate = $disposals_worksheet->getCell($cells['DIRECTORATE'])->getValue();
                $protocol = $disposals_worksheet->getCell($cells['PROTOCOL'])->getValue();
                $action = $disposals_worksheet->getCell($cells['ACTION'])->getValue();
                $subject = $disposals_worksheet->getCell($cells['SUBJECT'])->getValue();
                $rowiterator = $disposals_worksheet->getRowIterator($base_disposalsdata_row, null);
                                
                $localdir_dec = new DisposalLocaldirdecision();
                $localdir_dec->localdirdecision_protocol = $protocol;
                $localdir_dec->localdirdecision_action = $action;
                $localdir_dec->localdirdecision_subject = $subject;
                $localdir_dec->deleted = 0;
                $localdir_dec->archived = 0;
                
                if(!$localdir_dec->save()) {
                    //echo "<pre>"; print_r($localdir_dec->errors); echo "</pre>"; die();
                    throw new Exception("(@localdir_save)");
                }
                
                //$disposals = "";
                $is_empty_row = false;
                foreach ($rowiterator as $row) {
                    if($is_empty_row)
                        break;
                    $currentrow_index = $row->getRowIndex();
                    
                    $currentteacher_am = $disposals_worksheet->getCellByColumnAndRow($disposals_columns['AM'], $currentrow_index)->getValue();

                    $teacher_model = Teacher::findOne(['teacher_registrynumber' => $currentteacher_am]);
                    
                    if(!$teacher_model) {
                        $teacher_model = new Teacher();
                        $teacher_model->teacher_registrynumber = $currentteacher_am;
                        $teacher_model->teacher_surname = $disposals_worksheet->getCellByColumnAndRow($disposals_columns['SURNAME'], $currentrow_index)->getValue();
                        $teacher_model->teacher_name = $disposals_worksheet->getCellByColumnAndRow($disposals_columns['NAME'], $currentrow_index)->getValue();
                        $teacher_model->school_id = Schoolunit::findOne(['school_name' => $disposals_worksheet->getCellByColumnAndRow($disposals_columns['ORGANIC_SCHOOL'], $currentrow_index)->getValue()])['school_id'];
                        echo Schoolunit::find()->where(['school_name' => $disposals_worksheet->getCellByColumnAndRow($disposals_columns['ORGANIC_SCHOOL'], $currentrow_index)->getValue()])->createCommand()->rawSql; die();
                        
                        /* Find the specialisation_id of the teacher */
                        //echo $disposals_worksheet->getCellByColumnAndRow($disposals_columns['SPECIALISATION'], $currentrow_index)->getValue(); die();
                        $specialisation = mb_substr($disposals_worksheet->getCellByColumnAndRow($disposals_columns['SPECIALISATION'], $currentrow_index)->getValue(), 0, 7, 'UTF-8');                        
                        $specialisation_with_blank = mb_substr($specialisation, 0, 2) . ' ' . mb_substr($specialisation, 2, 5, 'UTF-8');
                        if(mb_substr($specialisation, 4, 1, 'UTF-8') != '.') {
                            $specialisation = mb_substr($specialisation, 0, 5);
                            $specialisation_with_blank = mb_substr($specialisation_with_blank, 0, 6);
                        }
                        $specialisation_id = Specialisation::find()->where(['code' => $specialisation])->orWhere(['code' => $specialisation_with_blank])->one()['id'];
                        //echo Specialisation::find()->where(['code' => $specialisation])->orWhere(['code' => $specialisation_with_blank])->createCommand()->rawSql; die();
                        
                        $teacher_model->specialisation_id = $specialisation_id;
                        echo "<pre>"; print_r($teacher_model); echo "</pre>"; die();
                        if(!$teacher_model->save()) {
                            echo "<pre>"; print_r($teacher_model->errors); echo "</pre>"; die();
                            throw new Exception("(@teacher_save)");
                        }
                        //echo $specialisation; die();
                    }
                    
                    $disposalCellIterator = $row->getCellIterator($disposals_columns['AM'], $disposals_columns['DISPOSAL_DUTY']);
                    
                    $disposal = new Disposal();
                    $disposal->disposal_startdate = $disposals_worksheet->getCellByColumnAndRow($disposals_columns['START_DATE'], $currentrow_index)->getValue();
                    $disposal->disposal_enddate = $disposals_worksheet->getCellByColumnAndRow($disposals_columns['END_DATE'], $currentrow_index)->getValue();
                    $disposal->disposal_hours = $disposals_worksheet->getCellByColumnAndRow($disposals_columns['HOURS'], $currentrow_index)->getValue();
                    $disposal->disposalreason_id = DisposalReason::findOne(['disposalreason_id' => $disposals_worksheet->getCellByColumnAndRow($disposals_columns['REASON'], $currentrow_index)->getValue()])['disposalreason_id'];
                    $disposal->disposalworkobj_id = DisposalWorkobj::findOne(['disposalworkobj_description' => $disposals_worksheet->getCellByColumnAndRow($disposals_columns['DUTY'], $currentrow_index)->getValue()])['disposalworkobj_id'];
                    $disposal->teacher_id = $teacher_model->teacher_id;
                    $disposal->school_id = Schoolunit::findOne(['school_name' => $disposals_worksheet->getCellByColumnAndRow($disposals_columns['DISPOSAL_SCHOOL'], $currentrow_index)->getValue()]);
                    $disposal->deleted = 0;
                    $disposal->archived = 0;
                    $disposal->localdirdecision_id = $localdir_dec->localdirdecision_id;

                    if(!$disposal->save()) {
                        //echo "<pre>"; print_r($disposal->errors); echo "</pre>"; die();
                        throw new Exception("(@disposal_save)");
                    }                    
                }                
                
                $transaction->commit();
                Yii::$app->session->addFlash('success', DisposalModule::t('modules/disposal/app', "The disposals were imported successfully."));
                return $this->redirect(['index']);
            }
            else {
                return $this->render('importdisposals', [
                    'import_model' => $import_model,
                ]);
            }
        }
        catch (Exception $exc) {
            $transaction->rollBack();
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', "Error in importing disposals. " . $exc->getMessage()));
            return $this->redirect(['index']);
        }
    }
        
    
    /**
     * Finds the Disposal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Disposal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Disposal::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
 