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
    public function actionCreate($id)
    {
        $model = new SchtransportTransport();     
        $tblprogram = Yii::$app->db->tablePrefix . 'schtransport_program';
        $tblmeeting = Yii::$app->db->tablePrefix . 'schtransport_meeting';
        $meetings = SchtransportMeeting::find()->all();//->innerJoin($tblprogram, $tblprogram . 'program_id' . '='. $tblmeeting . 'program_id')->andWhere([$tblprogram . 'programcategory_id' => $id]);
        $schools = Schoolunit::find()->all();
        $meeting_model = new SchtransportMeeting();
        $program_model = new SchtransportProgram();
        $program_model->programcategory_id = $id;
        
        try{
            if ($model->load(Yii::$app->request->post())
                && $meeting_model->load(Yii::$app->request->post())
                && $program_model->load(Yii::$app->request->post())){
            
                $transaction = Yii::$app->db->beginTransaction();
                
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
                    'meetings' => $meetings,
                    'schools' => $schools]);
            }
        }
        catch(Exception $exc){
            $transaction->rollBack();
            Yii::$app->session->addFlash('danger', Module::t('modules/schooltransport/app', $exc->getMessage()));
            return $this->redirect('create', [   'model' => $model,
                'meeting_model' => $meeting_model,
                'program_model' => $program_model,
                'meetings' => $meetings,
                'schools' => $schools]);
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
        $meeting_model = SchtransportMeeting::findOne(['meeting_id' => $model->meeting_id]);
        $schools = Schoolunit::find()->all();
        $program_model = SchtransportProgram::findOne(['program_id' => $meeting_model->program_id]);
        $meetings = SchtransportMeeting::find()->all();

        try{
            if ($model->load(Yii::$app->request->post())
                && $meeting_model->load(Yii::$app->request->post())
                && $program_model->load(Yii::$app->request->post())){
                    
                $transaction = Yii::$app->db->beginTransaction();
                
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
                    'meetings' => $meetings,
                    'schools' => $schools]);
            }
        }
        catch(Exception $exc){
            $transaction->rollBack();
            Yii::$app->session->addFlash('danger', Module::t('modules/schooltransport/app', $exc->getMessage()));
            return $this->redirect('update', [   'model' => $model,
                'meeting_model' => $meeting_model,
                'program_model' => $program_model,
                'meetings' => $meetings,
                'schools' => $schools]);
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
