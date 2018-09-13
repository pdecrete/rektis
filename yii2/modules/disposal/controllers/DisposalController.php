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
        
        $searchModel = new DisposalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $archived);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'archived' => $archived
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
            $schools = Schoolunit::find()->all();
            $specialisations = Specialisation::find()->all();
            $disposal_reasons = DisposalReason::find()->all();
            $disposal_workobjs = DisposalWorkobj::find()->all();
            $model = new Disposal();           
                       
            $disposal_hours = Disposal::getHourOptions();
            
            if ($model->load(Yii::$app->request->post()) 
                && $teacher_model->load(Yii::$app->request->post())) {
                                    
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
            
            $teacher_model = Teacher::findOne(['teacher_id' => $model->teacher_id]);
            $schools = Schoolunit::find()->all();
            $specialisations = Specialisation::find()->all();
            $disposal_reasons = DisposalReason::find()->all();
            $disposal_workobjs = DisposalWorkobj::find()->all();
            
            $disposal_hours = Disposal::getHourOptions();
                
            if ($model->load(Yii::$app->request->post())
                && $teacher_model->load(Yii::$app->request->post())) {
                             
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
        try {
            Yii::$app->session->addFlash('success', DisposalModule::t('modules/disposal/app', "The disposals were imported successfully."));
            return $this->redirect(['index']);
        }
        catch (Exception $exc) {
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', "The teacher disposal deletion failed."));
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
