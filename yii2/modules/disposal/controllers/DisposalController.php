<?php

namespace app\modules\disposal\controllers;

use DateTime;
use Yii;
use app\modules\disposal\models\Disposal;
use app\modules\disposal\models\DisposalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
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

    /**
     * Lists all Disposal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DisposalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Disposal model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
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
                
                if(!$teacher_model->save()) {                    
                    throw new Exception("Error in saving the teacher details in the database.");
                }
                $model->teacher_id = $teacher_model->teacher_id;
                
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
            $transaction = Yii::$app->db->beginTransaction();
            $model = $this->findModel($id);
            
            $teacher_model = Teacher::findOne(['teacher_id' => $model->teacher_id]);
            $schools = Schoolunit::find()->all();
            $specialisations = Specialisation::find()->all();
            $disposal_reasons = DisposalReason::find()->all();
            $disposal_workobjs = DisposalWorkobj::find()->all();
            
            $disposal_hours = Disposal::getHourOptions();
                
            if ($model->load(Yii::$app->request->post())
                && $teacher_model->load(Yii::$app->request->post())) {
                             
                //echo $model->disposal_endofteachingyear_flag; die();
                    
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
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
