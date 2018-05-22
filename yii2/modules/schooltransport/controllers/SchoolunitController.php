<?php

namespace app\modules\schooltransport\controllers;

use Yii;
use app\modules\schooltransport\Module;
use app\modules\schooltransport\models\Schoolunit;
use app\modules\schooltransport\models\SchoolunitSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\modules\schooltransport\models\Directorate;

/**
 * SchoolunitController implements the CRUD actions for Schoolunit model.
 */
class SchoolunitController extends Controller
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
                    ['actions' => ['index', 'view'], 'allow' => true, 'roles' => ['schtransport_viewer']],
                    ['actions' => ['massupdate'], 'allow' => true, 'roles' => ['financial_editor']]
                ]
            ]            
        ];
    }

    
    
    /**
     * Lists all Schoolunit models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SchoolunitSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Schoolunit model.
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
     * Creates a new Schoolunit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    /*
    public function actionCreate()
    {
        $model = new Schoolunit();
        $directorates = Directorate::find()->all();
        
        try{                
            if ($model->load(Yii::$app->request->post())){
                if(!$model->save())
                    throw new Exception("Failure in creating school unit.");
                Yii::$app->session->addFlash('success', Module::t('modules/schooltransport/app', "The school unit was created successfully"));
                return $this->redirect(['index']);
            }
            else {
                return $this->render('create', ['model' => $model, 'directorates' => $directorates]);
            }
        }
        catch (Exception $exc){
            Yii::$app->session->addFlash('danger', Module::t('modules/schooltransport/app', $exc->getMessage()));
            return $this->redirect('create', ['model' => $model, 'directorates' => $directorates]);
        }
    }*/

    /**
     * Updates an existing Schoolunit model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    /*
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $directorates = Directorate::find()->all();
        
        try{
            if ($model->load(Yii::$app->request->post())){
                
                if(!$model->save()) {
                    throw new Exception("Failure in updating school unit");
                }
                Yii::$app->session->addFlash('success', Module::t('modules/schooltransport/app', "The school unit was updated successfully"));
                return $this->redirect(['index']);
            }
            else {
                return $this->render('update', ['model' => $model, 'directorates' => $directorates]);
            }
        }
        catch(Exception $exc){
            Yii::$app->session->addFlash('danger', Module::t('modules/schooltransport/app', $exc->getMessage()));
            return $this->redirect('create', ['model' => $model, 'directorates' => $directorates]);            
        }
    }*/

    /**
     * Deletes an existing Schoolunit model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    /*
    public function actionDelete($id)
    {
        try{
            if(!$this->findModel($id)->delete())
                throw new Exception("Failure in deleting school unit");
            Yii::$app->session->addFlash('success', Module::t('modules/schooltransport/app', "The school unit was deleted successfully"));
            return $this->redirect(['index']);
        }
        catch (Exception $exc){
            Yii::$app->session->addFlash('danger', Module::t('modules/schooltransport/app', $exc->getMessage()));
            return $this->redirect('index');            
        }
        
    }*/

    
    public function actionMassupdate(){
        $edu_admins = [ [15, "ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΗΡΑΚΛΕΙΟΥ"], [25, "ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΧΑΝΙΩΝ"], [100, "ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΡΕΘΥΜΝΟΥ"], [95, "ΔΙΕΥΘΥΝΣΗ Δ.Ε. ΛΑΣΙΘΙΟΥ"],
            [41, "ΔΙΕΥΘΥΝΣΗ Π.Ε. ΗΡΑΚΛΕΙΟΥ"], [60, "ΔΙΕΥΘΥΝΣΗ Π.Ε. ΧΑΝΙΩΝ"], [75, "ΔΙΕΥΘΥΝΣΗ Π.Ε. ΡΕΘΥΜΝΟΥ"], [72, "ΔΙΕΥΘΥΝΣΗ Π.Ε. ΛΑΣΙΘΙΟΥ"]
        ];
        
        try{
            $params = array(
                "region_edu_admin" => 53,
                "pagesize" => 500,
                "page" => 1,
                "edu_admin" => null
            );
                        
            $curl = curl_init();        
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");            
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            
            $api_url = "https://mm.sch.gr/api/units";
            curl_setopt($curl, CURLOPT_URL, $api_url); 

            $transaction = Yii::$app->db->beginTransaction();
            /***************** Περιφερειακή Διεύθυνση ********************/
            
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
            $data = curl_exec($curl);
            $data = json_decode($data);
            
            if(curl_error($curl))
                throw new \Exception(curl_error($curl));
                
            $school_names = $data->data;
            foreach ($school_names as $school){
                $school_model = new Schoolunit();
                $school_model->directorate_id = 53;
                $school_model->school_id = $school->mm_id;
                $school_model->school_name = $school->name;
                $school_model->save();
            }          
            
            /**************** Διευθύνσεις Α/θμιας & Β/θμιας **************/
            unset($params['region_edu_admin']);
            foreach($edu_admins as $edu_admin){
                $params['edu_admin'] = $edu_admin[1];
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode( $params ));
                $data = curl_exec($curl);        
                $data = json_decode($data);
                
                //echo $data->pagination->maxPage;
                
                if(curl_error($curl))
                    throw new \Exception(curl_error($curl));
        
                $school_names = $data->data;
                foreach ($school_names as $school){
                    $school_model = new Schoolunit();
                    $school_model->directorate_id = $edu_admin[0];
                    $school_model->school_id = $school->mm_id;
                    $school_model->school_name = $school->name;
                    $school_model->save();
                }
            }           
            curl_close($curl);
            $transaction->commit();
            
            $user = Yii::$app->user->identity->username;            
            Yii::info('User ' . $user . ' updated school units data retrieved from MySchool.', 'schooltransport');
            
            Yii::$app->session->addFlash('success', Module::t('modules/schooltransport/app', "The schools' details were updated in the database."));
            return $this->redirect(['index']);
        }
        catch(\Exception $exc){
            $transaction->rollBack();
            Yii::$app->session->addFlash('danger', $exc->getMessage());
            return $this->redirect(['index']);
        }
    }
       
    
    /**
     * Finds the Schoolunit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Schoolunit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Schoolunit::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
