<?php

namespace app\modules\schooltransport\controllers;

use Yii;
use app\modules\schooltransport\Module;
use app\modules\schooltransport\models\SchtransportTransport;
use app\modules\schooltransport\models\SchtransportTransportSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\base\Exception;
use yii\filters\VerbFilter;
use app\modules\schooltransport\models\SchtransportProgramcategory;
use app\modules\schooltransport\models\SchtransportMeeting;
use app\modules\schooltransport\models\SchtransportProgram;
use app\modules\schooltransport\models\Schoolunit;

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
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SchtransportTransport model.
     * If creation is successful, the browser will be redirected to the 'view' page.
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
        $typeahead_data = array();
        $typeahead_data['COUNTRIES'] = SchtransportMeeting::find()->select('meeting_country')->column();
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
                                                'sep' => $sep]);
        }
    }
    
    
    /**
     * Updates an existing SchtransportTransport model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $meeting = SchtransportMeeting::findOne(['meeting_id' => $model->meeting_id]);        
        $program = SchtransportProgram::findOne(['program_id' => $meeting->program_id]);        
        $programcateg = SchtransportProgramcategory::findOne(['programcategory_id' => $program->program_id]);
        
        $pr_categ = $programcateg->programcategory_id;
        $sep = ($pr_categ == 3 || $programcateg->programcategory_programparent == 3) ? 1: 0;
        
        if($sep == 1)
            $schools = Schoolunit::find()->where(['like', 'school_name', 'ΕΥΡΩΠΑ'])->all();
        else
            $schools = Schoolunit::find()->all();
        
        $meeting_model = SchtransportMeeting::findOne(['meeting_id' => $model->meeting_id]);        
        $program_model = SchtransportProgram::findOne(['program_id' => $meeting_model->program_id]);
        $typeahead_data = array();
        $typeahead_data['COUNTRIES'] = SchtransportMeeting::find()->select('meeting_country')->column();
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
                if(!$model->save())
                    throw new Exception("Failure in creating the school transportation.");
                    
                $transaction->commit();
                Yii::$app->session->addFlash('success', Module::t('modules/schooltransport/app', "The school transport was created successfully."));
                return $this->redirect(['index']); 
            }
            else {
                return $this->render('update', [ 'model' => $model,
                    'meeting_model' => $meeting_model,
                    'program_model' => $program_model,
                    'schools' => $schools,
                    'typeahead_data' => $typeahead_data,
                    'sep' => $sep]);
            }
        }
        catch(Exception $exc){
            $transaction->rollBack();
            Yii::$app->session->addFlash('danger', Module::t('modules/schooltransport/app', $exc->getMessage()));
            return $this->redirect('update', [   'model' => $model,
                'meeting_model' => $meeting_model,
                'program_model' => $program_model,
                'schools' => $schools,
                'typeahead_data' => $typeahead_data,
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
