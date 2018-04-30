<?php

namespace app\modules\schooltransport\controllers;

use Yii;
use PhpOffice\PhpWord\TemplateProcessor;
use app\modules\schooltransport\Module;
use app\modules\schooltransport\models\SchtransportTransport;
use app\modules\schooltransport\models\SchtransportTransportSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\base\Exception;
use yii\filters\VerbFilter;
use app\modules\schooltransport\models\SchtransportProgramcategory;
use app\modules\schooltransport\models\SchtransportMeeting;
use app\modules\schooltransport\models\SchtransportProgram;
use app\modules\schooltransport\models\Schoolunit;
use app\modules\schooltransport\models\SchtransportCountry;
use app\modules\schooltransport\models\SchtransportState;
use app\modules\schooltransport\models\SchtransportTransportstate;

/**
 * SchtransportTransportController implements the CRUD actions for SchtransportTransport model.
 */
class SchtransportTransportController extends Controller
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

    /**
     * Lists all SchtransportTransport models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SchtransportTransportSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $programcategs = array();
        $program_parentcategs = SchtransportProgramcategory::findAll(['programcategory_programparent' => NULL]);
        foreach ($program_parentcategs as $index=>$parentcateg){
            $programcategs[$parentcateg['programcategory_id']]['TITLE'] = $parentcateg['programcategory_programtitle'];
            $programcategs[$parentcateg['programcategory_id']]['PROGRAMCATEG_ID'] = $parentcateg['programcategory_id'];
            $programcategs[$parentcateg['programcategory_id']]['SUBCATEGS'] = SchtransportProgramcategory::findAll(['programcategory_programparent' => $parentcateg['programcategory_id']]);
        }
        //echo "<pre>"; print_r($programcategs); echo "</pre>";
        //die();
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'programcategs' => $programcategs
        ]);
    }

    /**
     * Displays a single SchtransportTransport model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {//echo "Hallo"; die();
       return $this->actionUpdate($id, true);        
    }

    /**
     * Creates a new SchtransportTransport model.
     * If creation is successful, the browser will be redirected to the 'view' page. 
     * Parameter $id is the program category id and $sep denotes whether the transport is related to the European School.
     * @param integer $id
     * @param integer $sep
     * @return mixed
     */
    public function actionCreate($id, $sep = 0)
    {
        $model = new SchtransportTransport();     
        $tblprogram = Yii::$app->db->tablePrefix . 'schtransport_program';
        $tblmeeting = Yii::$app->db->tablePrefix . 'schtransport_meeting';
        if($sep == 1)
            $schools = Schoolunit::find()->where(['like', 'school_name', 'ΕΥΡΩΠΑ'])->all();
        else
            $schools = Schoolunit::find()->all();
        
        $meeting_model = new SchtransportMeeting();
        $program_model = new SchtransportProgram();
        $program_model->programcategory_id = $id;
        $countries = SchtransportCountry::find()->select('country_name')->column();
        $typeahead_data = array();
        $typeahead_data['COUNTRIES'] = array_merge(SchtransportMeeting::find()->select('meeting_country')->column(), $countries);
        $typeahead_data['CITIES'] = SchtransportMeeting::find()->select('meeting_city')->column();
        $typeahead_data['PROGRAMCODES'] = SchtransportProgram::find()->select('program_code')->column();
        $typeahead_data['PROGRAMTITLES'] = SchtransportProgram::find()->select('program_title')->column();        
        
        try{
            $transaction = Yii::$app->db->beginTransaction();
            if ($model->load(Yii::$app->request->post())
                && $meeting_model->load(Yii::$app->request->post())
                && $program_model->load(Yii::$app->request->post())){
                
                $existing_program = SchtransportProgram::findOne(['program_code' => $program_model->program_code]);
                $program_exists = !(count($existing_program) == 0);
                if($program_exists)
                    $program_model->program_id = $existing_program->program_id;
                else {
                    if(!$program_model->save())
                        throw new Exception("Failure in creating the transportation");
                }
                //echo 'hallo'; die();
                $meeting_model->program_id = $program_model->program_id;
                
                //echo "<pre>"; print_r($meeting_model); echo "</pre>";die();
                //$meeting_model->program_id = 4;
                
                if($meeting_model->isNewRecord){
                    if(!$meeting_model->save())
                        throw new Exception("Failure in creating the transportation");
                }
                $model->meeting_id = $meeting_model->meeting_id;                
                if(!$model->save())
                    throw new Exception("Failure in creating the school transportation.");
                $model->transport_approvalfile = $this->createApprovalFile($model, $meeting_model, $program_model);
                /* Save model twice, the first one for creating the transport_id and the
                 second to save the file with filename that has the transport_id as part of it.*/
                if(!$model->save()) 
                    throw new Exception("Failure in creating the school transportation.");
                $transaction->commit();
                Yii::$app->session->addFlash('success', Module::t('modules/schooltransport/app', "The school transport was created successfully."));
                return $this->redirect(['index']); 
            }
            else {
                return $this->render('create', [ 'model' => $model,
                    'meeting_model' => $meeting_model,
                    'program_model' => $program_model,
                    'schools' => $schools,
                    'typeahead_data' => $typeahead_data,
                    'programcateg_id' => $id,
                    'sep' => $sep]);
            }
        }
        catch(Exception $exc){
            $transaction->rollBack();
            Yii::$app->session->addFlash('danger', Module::t('modules/schooltransport/app', $exc->getMessage()));
            return $this->redirect('create', [  'model' => $model,
                                                'meeting_model' => $meeting_model,
                                                'program_model' => $program_model,                
                                                'schools' => $schools,
                                                'typeahead_data' => $typeahead_data,
                                                'programcateg_id' => $id,
                                                'sep' => $sep]);
        }
    }
    
    
    /**
     * Updates an existing SchtransportTransport model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * Parameter $id is the program category id and $sep denotes whether the transport is related to the European School.
     * $readonly_mode parameter is used just for the view of the details of the approval (disabled fields) 
     * @param integer $id
     * @param integer $readonly_mode
     * @return mixed
     */
    public function actionUpdate($id, $readonly_mode = false)
    {        
        $model = $this->findModel($id);
        if($model->getStates()->count() > 0){
            Yii::$app->session->addFlash('danger', Module::t('modules/schooltransport/app', "The school transport cannot be updated, because it is not in initial state."));
            return $this->redirect(['index']);
        }
        $meeting = SchtransportMeeting::findOne(['meeting_id' => $model->meeting_id]);        
        $program = SchtransportProgram::findOne(['program_id' => $meeting->program_id]);
        $programcateg = SchtransportProgramcategory::findOne(['programcategory_id' => $program->programcategory_id]);
        $pr_categ = $programcateg->programcategory_id;
        
        $sep = ($pr_categ == 3 || $programcateg->programcategory_programparent == 3) ? 1: 0;
        
        if($sep == 1)
            $schools = Schoolunit::find()->where(['like', 'school_name', 'ΕΥΡΩΠΑ'])->all();
        else
            $schools = Schoolunit::find()->all();
        
        $meeting_model = SchtransportMeeting::findOne(['meeting_id' => $model->meeting_id]);        
        $program_model = SchtransportProgram::findOne(['program_id' => $meeting_model->program_id]);
        $typeahead_data = array();
        $countries = SchtransportCountry::find()->select('country_name')->column();
        $typeahead_data['COUNTRIES'] = array_merge(SchtransportMeeting::find()->select('meeting_country')->column(), $countries);
        $typeahead_data['CITIES'] = SchtransportMeeting::find()->select('meeting_city')->column();
        $typeahead_data['PROGRAMCODES'] = SchtransportProgram::find()->select('program_code')->column();
        $typeahead_data['PROGRAMTITLES'] = SchtransportProgram::find()->select('program_title')->column();

        try{
            $transaction = Yii::$app->db->beginTransaction();
            if ($model->load(Yii::$app->request->post())
                && $meeting_model->load(Yii::$app->request->post())
                && $program_model->load(Yii::$app->request->post())){
                
                $program_exists = !(count(SchtransportProgram::findOne(['program_code' => $program_model->program_code])) == 0);
                if(!$program_exists){
                    if(!$program_model->save())
                        throw new Exception("Failure in creating the transportation");
                }
                
                $meeting_model->program_id = $program_model->program_id;
                if(!$meeting_model->save())
                    throw new Exception("Failure in creating the transportation");
                
                $model->meeting_id = $meeting_model->meeting_id;
                
                /* delete old file: */
                if(file_exists(Yii::getAlias("@vendor/admapp/exports/schooltransports/") . $model->transport_approvalfile))
                    unlink(Yii::getAlias("@vendor/admapp/exports/schooltransports/") . $model->transport_approvalfile);
                
                $filename = $this->createApprovalFile($model, $meeting_model, $program_model);
                //unlink($filename);
                $model->transport_approvalfile = $filename;
                if(!$model->save())
                    throw new Exception("Failure in creating the school transportation.");
                                    
                $transaction->commit();
                Yii::$app->session->addFlash('success', Module::t('modules/schooltransport/app', "The school transport was updated successfully."));
                return $this->redirect(['index']); 
            }
            else {
                $view_file = 'update';
                if($readonly_mode)
                    $view_file = 'view';
                 
                return $this->render($view_file, [ 'model' => $model,
                        'meeting_model' => $meeting_model,
                        'program_model' => $program_model,
                        'schools' => $schools,
                        'typeahead_data' => $typeahead_data,
                        'programcateg_id' => $pr_categ,
                        'sep' => $sep]);
            }
        }
        catch(Exception $exc){
            $transaction->rollBack();
            Yii::$app->session->addFlash('danger', Module::t('modules/schooltransport/app', $exc->getMessage()));
            $view_file = 'update';
            if($readonly_mode)
                $view_file = 'view';
            
            return $this->redirect($view_file, ['model' => $model,
                                                'meeting_model' => $meeting_model,
                                                'program_model' => $program_model,
                                                'schools' => $schools,
                                                'typeahead_data' => $typeahead_data,
                                                'programcateg_id' => $pr_categ,
                                                'sep' => $sep]);
        }
    }

    /**
     * Deletes an existing SchtransportTransport model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $meeting_model = $model->getMeeting()->one();
         
        try{
            $transaction = Yii::$app->db->beginTransaction();

            if(SchtransportTransportstate::find()->where(['transport_id' => $id])->count() > 0)
                throw new Exception('Failure in deleting the school transport, because it is not in initial state.');

            /* delete old file: */
            if(file_exists(Yii::getAlias("@vendor/admapp/exports/schooltransports/") . $model->transport_approvalfile))
                unlink(Yii::getAlias("@vendor/admapp/exports/schooltransports/") . $model->transport_approvalfile);
                    
            if(!$model->delete())
                throw new Exception('Failure in deleting the school transport.');

            if(!$meeting_model->delete())
                throw new Exception('Failure in deleting the school transport.');
                            
            $transaction->commit();
            
            Yii::$app->session->addFlash('success', Module::t('modules/schooltransport/app', "The school transport was deleted successfully."));
            return $this->redirect(['index']); 
        }
        catch (Exception $exc){
            $transaction->rollBack();
            Yii::$app->session->addFlash('danger', Module::t('modules/schooltransport/app', $exc->getMessage()));
            return $this->redirect('index');
        }
        
        return $this->redirect(['index']);
    }
    
    
    private function getProgramAction($programcateg_model)
    {
        $program_action = "";
        if($programcateg_model->programcategory_id == 4)
            $program_action = "KA1";
        else if($programcateg_model->programcategory_id == 5)
            $program_action = "KA2";
        else if($programcateg_model->programcategory_id == 10)
            $program_action = "Polyhmerh";
        else if($programcateg_model->programcategory_id == 11)
            $program_action = "Vouli";
        
        return $program_action;
    }
    
    
    private function createApprovalFile($transport_model, $meeting_model, $program_model)
    {
        $school_model = Schoolunit::findOne(['school_id' => $transport_model['school_id']]);
        $directorate_model = $school_model->getDirectorate()->one();
        
        $programcateg_model = SchtransportProgramcategory::findOne(['programcategory_id' => $program_model['programcategory_id']]);
        //echo "<pre>"; print_r($programcateg_model);  echo "</pre>";die();
        
        $program_action = $this->getProgramAction($programcateg_model);
            
        //echo $program_action; die();
        /*$fileName = Yii::getAlias("@vendor/admapp/exports/schooltransports/" . $program_action . "_" .
                                    str_replace(" ", "_", $school_model->school_name) . "_" . $meeting_model['meeting_country'] . "_" .
                                    $transport_model['transport_id'] . ".docx");*/
        $fileName = $this->getFilename( $program_action, $school_model->school_name,
                                    $meeting_model['meeting_country'], $meeting_model['meeting_city'], $transport_model['transport_id']);
        $fullpath_fileName = Yii::getAlias("@vendor/admapp/exports/schooltransports/") . $this->getFilename( $program_action, $school_model->school_name, 
                                    $meeting_model['meeting_country'], $meeting_model['meeting_city'], $transport_model['transport_id']);
            
        $template_path = "@vendor/admapp/resources/schooltransports/" . $program_action . ".docx";
        
        //echo $template_path; die();
        $templateProcessor = new TemplateProcessor(Yii::getAlias($template_path));
        if(in_array($programcateg_model->programcategory_id, [5, 6, 7, 8, 9, 10, 11])){
            $templateProcessor->setValue('students', $transport_model['transport_students']);
            $templateProcessor->setValue('head_teacher', $transport_model['transport_headteacher']);
        }
        if(in_array($programcateg_model->programcategory_id, [6, 7, 8, 9, 10, 11])){
            $templateProcessor->setValue('school_record', $transport_model['transport_schoolrecord']);
            $templateProcessor->setValue('class', $transport_model['transport_class']);
        }
        
        $templateProcessor->setValue('contactperson', Yii::$app->user->identity->surname . ' ' . Yii::$app->user->identity->name);
        $templateProcessor->setValue('postaladdress', Yii::$app->params['address']);
        $templateProcessor->setValue('phonenumber', Yii::$app->params['schooltransport_telephone']);
        $templateProcessor->setValue('fax', Yii::$app->params['fax']);
        $templateProcessor->setValue('email', Yii::$app->params['email']);
        $templateProcessor->setValue('webaddress', Yii::$app->params['web_address']);
        $templateProcessor->setValue('date', date('d/m/Y'));
        $templateProcessor->setValue('protocol', $transport_model['transport_pde_protocol']);
        $templateProcessor->setValue('school', $school_model->school_name);
        $templateProcessor->setValue('teachers', $transport_model['transport_teachers']);        
        $templateProcessor->setValue('country', $meeting_model['meeting_country']);
        $templateProcessor->setValue('local_directorate_protocol', $transport_model['transport_localdirectorate_protocol']);
        $templateProcessor->setValue('local_directorate', $directorate_model['directorate_name']);
        $templateProcessor->setValue('programcateg_title', $programcateg_model->programcategory_programtitle);
        $templateProcessor->setValue('programcateg_description', $programcateg_model->programcategory_programdescription);
        $templateProcessor->setValue('program_title', $program_model['program_title']);
        $templateProcessor->setValue('program_code', $program_model['program_code']);
        $templateProcessor->setValue('transport_start', date_format(date_create($transport_model['transport_startdate']), 'd-m-Y'));
        $templateProcessor->setValue('transport_end', date_format(date_create($transport_model['transport_enddate']), 'd-m-Y'));
        $templateProcessor->setValue('director_name', Yii::$app->params['director_name']);
        $templateProcessor->saveAs($fullpath_fileName);
        return $fileName;
    }
    
    
    private function getFilename($program_action, $school_name, $meeting_country, $meeting_city, $transport_id)
    {
        return $program_action . "_" . str_replace(" ", "_", $school_name) . "_" . $meeting_country . "_" . $meeting_city . "_" . $transport_id . ".docx";
    }
    
    private function getFilenamesigned($program_action, $school_name, $meeting_country, $meeting_city, $transport_id)
    {
        $filename = $this->getFilename($program_action, $school_name, $meeting_country, $meeting_city, $transport_id);
        $extension_pos = strrpos($filename, '.docx');
        return substr_replace($filename, '_signed.pdf', $extension_pos, strlen($filename));        
    }

    /**
     * Prints an existing SchtransportTransport model.     
     * @param integer $id
     * @return mixed
     */
    public function actionDownload($id){
        try{
            $transport_model = $this->findModel($id);
            
            if(is_null($transport_model))
                throw new Exception("The requested transport could not be found.");
            
            $school_model = Schoolunit::findOne(['school_id' => $transport_model['school_id']]);
            $meeting_model = $transport_model->getMeeting()->one();
            $program_model = SchtransportProgram::findOne(['program_id' => $meeting_model['program_id']]);
            $programcateg_model = SchtransportProgramcategory::findOne(['programcategory_id' => $program_model['programcategory_id']]);
            
            $program_action = $this->getProgramAction($programcateg_model);
            
            $file = Yii::getAlias("@vendor/admapp/exports/schooltransports/") . $transport_model->transport_approvalfile;
                    //$this->getFilename($program_action, $school_model->school_name, $meeting_model['meeting_country'], 
                    //                    $meeting_model['meeting_city'], $transport_model['transport_id']);

            if(!is_readable($file))
                throw new Exception("The decision file cannot be found.");
                    
            return Yii::$app->response->SendFile($file);
                                        
            return $this->redirect(['/schooltransport/schtransport-transport/index']);
                    
        }
        catch(Exception $e){
            Yii::$app->session->addFlash('danger', Module::t('modules/schooltransport/app', $e->getMessage()));
            return $this->redirect(['/schooltransport/schtransport-transport/index']);
        }
    }
    
    
    
    /**
     * Prints an existing SchtransportTransport model.
     * @param integer $id
     * @return mixed
     */
    public function actionDownloadsigned($id){
        try{
            $transport_model = $this->findModel($id);
            
            if(is_null($transport_model))
                throw new Exception("The requested transport could not be found.");
                
                $school_model = Schoolunit::findOne(['school_id' => $transport_model['school_id']]);
                $meeting_model = $transport_model->getMeeting()->one();
                $program_model = SchtransportProgram::findOne(['program_id' => $meeting_model['program_id']]);
                $programcateg_model = SchtransportProgramcategory::findOne(['programcategory_id' => $program_model['programcategory_id']]);
                
                $program_action = $this->getProgramAction($programcateg_model);
                
                $file = Yii::getAlias(Yii::$app->params['schooltransport_uploadfolder']) . $transport_model->transport_signedapprovalfile;
                //echo $file; die();
                
                if(!is_readable($file))
                    throw new Exception("The decision file cannot be found.");
                    
                    return Yii::$app->response->SendFile($file);
                    
                    return $this->redirect(['/schooltransport/schtransport-transport/index']);
                    
        }
        catch(Exception $e){
            Yii::$app->session->addFlash('danger', Module::t('modules/schooltransport/app', $e->getMessage()));
            return $this->redirect(['/schooltransport/schtransport-transport/index']);
        }
    }
    
    
    
    /**
     * Sets the transport state to the next state (e.g. if it is in the "Digital Signature" state, then the
     * state is set to "Protocol")
     * If the action is successful, the next visual indicator will be shown.
     * @param integer $id
     * @return mixed
     */
    public function actionForwardstate($id)
    {        
        try {            
            $trnsprt_model = $this->findModel($id);
            $existing_trnsportstate_models = $trnsprt_model->getTransportstates()->all();
            //$states =
            $count_transportstates = count($existing_trnsportstate_models);
            
            if($count_transportstates >= 3){
                throw new Exception();
            }
            $transportstate_model = new SchtransportTransportstate();
            $transportstate_model->state_id = $count_transportstates + 1;
            $transportstate_model->transport_id = $id;
            $state_name = SchtransportState::find()->where(['state_id' => $count_transportstates+1])->one()['state_name'];
            
            if ($transportstate_model->load(Yii::$app->request->post()) && $trnsprt_model->load(Yii::$app->request->post())) {
                $transaction = Yii::$app->db->beginTransaction();

                if($transportstate_model->state_id == 1){
                    $school_model = Schoolunit::findOne(['school_id' => $trnsprt_model['school_id']]);
                    $trnsprt_model->signedfile = UploadedFile::getInstance($trnsprt_model, 'signedfile');
                    $meeting_model = $trnsprt_model->getMeeting()->one();//->getProgram();
                    $program_model = SchtransportProgram::findOne(['program_id' => $meeting_model['program_id']]);
                    $programcateg_model = SchtransportProgramcategory::findOne(['programcategory_id' => $program_model['programcategory_id']]);
                    
                    $filename = $this->getFilenamesigned( $this->getProgramAction($programcateg_model), $school_model->school_name, 
                                                    $meeting_model['meeting_country'], $meeting_model['meeting_city'], $trnsprt_model->transport_id);
                    
                    $trnsprt_model->transport_signedapprovalfile = $filename;
                    if(!$trnsprt_model->upload($filename))
                        throw new Exception("Failed to forward transport state.");
                }
                if(!$trnsprt_model->save(false))
                    throw new Exception("Failed to forward transport state.");
                if(!$transportstate_model->save())
                    throw new Exception("Failed to forward transport state.");
                $transaction->commit();
                Yii::$app->session->addFlash('success', Module::t('modules/schooltransport/app', "The transport's approval state changed successfully."));
                return $this->redirect(['index']);
            }
            else{
                return $this->render('createstate', [
                    'transportstate_model' => $transportstate_model,                    
                    'state_name' => $state_name,
                    'trnsprt_model' => $trnsprt_model
                ]);
            }            
        }
        catch(Exception $e){
            $transaction->rollBack();
            Yii::$app->session->addFlash('danger', Module::t('modules/schooltransport/app', $e->getMessage()));
            return $this->redirect(['index']);
        }
    }
    
    
    /**
     * Backwards the transport state to the previous state (e.g. if it is in the "Protocol" state, then the
     * state is set to "Digital Signature")
     * If the action is successful, the proper visual indicators will be shown.
     * @param integer $id
     * @return mixed
     */
    public function actionBackwardstate($id)
    {
        try {
            $transaction = Yii::$app->db->beginTransaction();
            $transport_model = $this->findModel($id);
            $states_count = count(SchtransportTransportstate::findAll(['transport_id' => $id]));
            if($states_count == 0)
                throw new Exception();

            /* delete old file signed file if backwarded from state of digitally signed file: */
            if($states_count == 1){
                if(!is_null($transport_model->transport_signedapprovalfile) && file_exists(Yii::getAlias(Yii::$app->params['schooltransport_uploadfolder']) . $transport_model->transport_signedapprovalfile))
                    unlink(Yii::getAlias(Yii::$app->params['schooltransport_uploadfolder']) . $transport_model->transport_signedapprovalfile);
                $transport_model->transport_signedapprovalfile = null;
                if(!$transport_model->save())
                    throw new Exception();
            }
            
            if(!SchtransportTransportstate::findOne(['state_id' => $states_count])->delete())
                throw new Exception();
            
            $transaction->commit();
            Yii::$app->session->addFlash('success', Module::t('modules/schooltransport/app', "The transport's approval state changed successfully."));
            return $this->redirect(['index']);
        }
        catch(Exception $e){
            $transaction->rollBack();
            Yii::$app->session->addFlash('danger', Module::t('modules/schooltransport/app', "Failed to backward transport state."));
            return $this->redirect(['index']);
        }
    }
    
    
    /**
     * Update the details of the transport's approval state.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdatestate($state_id, $transport_id)
    {
        try {
            $trnsprt_model = $this->findModel($transport_id);            
            $transportstate_model = SchtransportTransportstate::find()->where(['transport_id' => $transport_id])->andWhere(['state_id' => $state_id])->one();
            $state_name = SchtransportState::find()->where(['state_id' => $state_id])->one()['state_name'];
            
            if ($transportstate_model->load(Yii::$app->request->post()) && $trnsprt_model->load(Yii::$app->request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                
                if($transportstate_model->state_id == 1){
                    $school_model = Schoolunit::findOne(['school_id' => $trnsprt_model['school_id']]);
                    $trnsprt_model->signedfile = UploadedFile::getInstance($trnsprt_model, 'signedfile');
                    $meeting_model = $trnsprt_model->getMeeting()->one();
                    $program_model = SchtransportProgram::findOne(['program_id' => $meeting_model['program_id']]);
                    $programcateg_model = SchtransportProgramcategory::findOne(['programcategory_id' => $program_model['programcategory_id']]);
                    
                    $filename = $this->getFilenamesigned( $this->getProgramAction($programcateg_model), $school_model->school_name,
                        $meeting_model['meeting_country'], $meeting_model['meeting_city'], $trnsprt_model->transport_id);
                    
                    $trnsprt_model->transport_signedapprovalfile = $filename;
                    if(!$trnsprt_model->upload($filename))
                        throw new Exception();
                }
                if(!$transportstate_model->save())
                    throw new Exception();
                $transaction->commit();
                Yii::$app->session->addFlash('success', Module::t('modules/schooltransport/app', "The transport's approval state changed successfully."));
                return $this->redirect(['index']);
            }
            else{
                return $this->render('updatestate', [
                    'transportstate_model' => $transportstate_model,
                    'state_name' => $state_name,
                    'trnsprt_model' => $trnsprt_model
                ]);
            }
        }
        catch(Exception $e){
            $transaction->rollBack();
            Yii::$app->session->addFlash('danger', Module::t('modules/schooltransport/app', "Failed to forward transport state."));
            return $this->redirect(['index']);
        }
    }
    
    
    /**
     * Finds the SchtransportTransport model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SchtransportTransport the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SchtransportTransport::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
