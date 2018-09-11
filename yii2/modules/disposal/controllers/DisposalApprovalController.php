<?php

namespace app\modules\disposal\controllers;

use Exception;
use Yii;
use PhpOffice\PhpWord\TemplateProcessor;
use app\modules\disposal\DisposalModule;
use app\modules\disposal\models\DisposalApproval;
use app\modules\disposal\models\DisposalApprovalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\base\Model;
use yii\filters\VerbFilter;
use app\modules\disposal\models\Disposal;
use app\modules\disposal\models\DisposalDisposalapproval;
use app\modules\schooltransport\models\Schoolunit;
use app\modules\schooltransport\models\Directorate;

/**
 * DisposalApprovalController implements the CRUD actions for DisposalApproval model.
 */
class DisposalApprovalController extends Controller
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
     * Lists all DisposalApproval models.
     * @return mixed
     */
    public function actionIndex()
    {       
        $searchModel = new DisposalApprovalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DisposalApproval model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id)
        ]);
    }

    
    /**
     * Creates a new DisposalApproval model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($selection = 0)
    {
        $disposal_ids = array();
        
        if(isset($_POST['disposal_ids'])){            
            $disposal_ids = unserialize($_POST['disposal_ids']);
        }
        else { 
            $disposal_ids = Yii::$app->request->post('selection');
            //echo "<pre>"; echo count($disposal_ids); echo "<pre>"; die();
            if (count($disposal_ids) == 0) {
                Yii::$app->session->addFlash('info', DisposalModule::t('modules/disposal/app', "Please select at least one disposal."));
                return $this->redirect(['disposal/index']);
            }
        }
        
        $model = new DisposalApproval();
        $disposalapproval_models = array();
        $disposals_models = array();
        $teacher_models = array();
        $school_models = array();
        $specialization_models = array();
        //echo "<pre>"; print_r($disposal_ids);   echo "</pre>"; die();
        foreach ($disposal_ids as $index=>$disposal_id){
            $disposals_models[$index] = Disposal::find()->where(['disposal_id' => $disposal_id])->one();
            $disposalapproval_models[$index] = new DisposalDisposalapproval();
            $disposalapproval_models[$index]->disposal_id = $disposal_id;
            $teacher_models[$index] = $disposals_models[$index]->getTeacher()->one();
            $school_models[$index] = $disposals_models[$index]->getSchool()->one();
            $specialization_models[$index] = $teacher_models[$index]->getSpecialisation()->one();
        }
        
        $directorate_id = Schoolunit::findOne(['school_id' => $teacher_models[0]['school_id']])['directorate_id'];
        $directorate_model = Directorate::findOne(['directorate_id' => $directorate_id]);
        foreach ($teacher_models as $teacher_model) {
            $teachers_school = Schoolunit::findOne(['school_id' => $teacher_model['school_id']]);    
            if ($teachers_school['directorate_id'] != $directorate_id) {
                Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', "Please select teachers of only one directorate."));
                return $this->redirect(['disposal/index']);
            }
        }


        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {            
            if($model->load(Yii::$app->request->post()) && Model::loadMultiple($disposalapproval_models, Yii::$app->request->post())) {
                
                $template_filename = "DISPOSALS_APPROVAL_GENERAL_TEMPLATE";
                $model->approval_file = $template_filename . '_' . $model->approval_regionaldirectprotocol . '_' . str_replace('-', '_', $model->approval_regionaldirectprotocoldate) . ".docx";
                $model->approval_signedfile = '-'; //TODO allow null (has been changed in migration)
                if(!$model->save()) {
                    throw new Exception("Failed to save the approval in the database.");
                }
                //echo "<pre>"; print_r($disposalapproval_models); echo "</pre>"; die();
                $disposals_counter = 0;
                foreach ($disposalapproval_models as $disposalapproval_model) {
                    if($disposalapproval_model->disposal_id == 0)
                        continue;
                    $disposals_counter++;
                    $disposal_model = Disposal::findOne($disposalapproval_model->disposal_id);
                    if(!$disposal_model)
                        throw new Exception("Failed to assign disposals to the approval1");
                    $disposal_model->archived = 1;
                    if(!$disposal_model->save())
                        throw new Exception("Failed to assign disposals to the approval2");
                    $disposalapproval_model->approval_id = $model->approval_id;
                    if(!$disposalapproval_model->save())
                        throw new Exception("Failed to assign disposals to the approval3");
                }
                if($disposals_counter == 0)
                    throw new Exception("Please select at least one disposal.");
                
                                
                $filename = "DISPOSALS_APPROVAL_GENERAL_TEMPLATE";
                $template_path = Yii::getAlias($this->module->params['disposal_templatepath']) . $filename . ".docx";
                $fullpath_fileName = Yii::getAlias($this->module->params['disposal_exportfolder']) . $filename . '_' . $model->approval_id . ".docx";
                
                if (!file_exists($template_path)) {
                    throw new Exception("The creation of the approval failed, because the template file for the approval does not exist.");
                }
                $template_path = Yii::getAlias($this->module->params['disposal_templatepath']) . $template_filename . ".docx";
                $fullpath_fileName = Yii::getAlias($this->module->params['disposal_exportfolder']) . $model->approval_file;
                
                $templateProcessor = new TemplateProcessor(Yii::getAlias($template_path));                
                $templateProcessor->setValue('regionaldirect_protocoldate', date_format(date_create($model->approval_regionaldirectprotocoldate), 'd-m-Y'));
                $templateProcessor->setValue('regionaldirect_protocol', $model->approval_regionaldirectprotocol);
                $templateProcessor->setValue('contactperson', Yii::$app->user->identity->surname . ' ' . Yii::$app->user->identity->name);
                $templateProcessor->setValue('postaladdress', Yii::$app->params['address']);
                $templateProcessor->setValue('phonenumber', $this->module->params['disposal_telephone']);
                $templateProcessor->setValue('fax', $this->module->params['disposal_fax']);
                $templateProcessor->setValue('email', Yii::$app->params['email']);
                $templateProcessor->setValue('webaddress', Yii::$app->params['web_address']);
                $templateProcessor->setValue('local_directorate', $directorate_model['directorate_name']);
                $templateProcessor->setValue('local_directorate_genitive', str_replace('Διεύθυνση', 'Διεύθυνσης', $directorate_model['directorate_name']));
                $templateProcessor->setValue('local_directorate_protocol', $model->approval_localdirectprotocol);
                $templateProcessor->setValue('local_directorate_decisionsubject', $model->approval_localdirectdecisionsubject);
                
                $teacher_disposals = '';
                //echo "<pre>"; print_r($teacher_models); echo "<pre>"; die();
                for($i = 0; $i < count($teacher_models); $i++) {
                    $teacher_disposals .= "- " . $teacher_models[$i]['teacher_surname'] . " " . $teacher_models[$i]['teacher_name'] . ", εκπαιδευτικός κλάδου ";
                    $teacher_disposals .= $specialization_models[$i]['code'] . ": διατίθεται "; 
                    $teacher_disposals .= "\n"
                }
                $templateProcessor->setValue('teacher_disposals', $teacher_disposals);
                
                $templateProcessor->setValue('director_name', Yii::$app->params['director_name']);
                $templateProcessor->saveAs($fullpath_fileName);
                    
                $transaction->commit();
                Yii::$app->session->addFlash('success', DisposalModule::t('modules/disposal/app', "The approval of the disposals was created successfully."));
                return $this->redirect(['disposal-approval/index']);
            }
            else {
                return $this->render('create', [
                    'model' => $model,
                    'disposals_models' => $disposals_models,
                    'disposalapproval_models' => $disposalapproval_models,
                    'teacher_models' => $teacher_models,
                    'school_models' => $school_models,
                    'specialization_models' => $specialization_models,
                    'disposal_ids' => $disposal_ids,
                    'selection' => 1
                ]);
            }
        }
        catch (Exception $exc) {
            $transaction->rollBack();
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', $exc->getMessage()));
            return $this->render('create', [
                'model' => $model,
                'disposals_models' => $disposals_models,
                'disposalapproval_models' => $disposalapproval_models,
                'teacher_models' => $teacher_models,
                'school_models' => $school_models,
                'specialization_models' => $specialization_models,
                'disposal_ids' => $disposal_ids,
                'selection' => 1
            ]);
        }   
    }

    /**
     * Updates an existing DisposalApproval model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->approval_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DisposalApproval model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            $transaction = Yii::$app->db->beginTransaction();
            $approval_model = $this->findModel($id);
            $approval_model->deleted = 1;
            if(!$approval_model->save())
                throw new Exception("The deletion of the disposals\' approval failed.");
            
            $disposal_ids = DisposalDisposalapproval::findAll(['approval_id' => $approval_model->approval_id]);
            //echo "<pre>"; print_r($disposal_ids); echo "</pre>"; die(); 
            foreach ($disposal_ids as $disposal_id) {
                $disposal_model = Disposal::find()->where(['disposal_id' => $disposal_id])->one();
                $disposal_model->archived = 0;
                if(!$disposal_model->save())
                    throw new Exception("The deletion of the disposals\' approval failed.");
            }
            
            $transaction->commit();
            unlink(Yii::getAlias($this->module->params['disposal_exportfolder']) . $approval_model->approval_file);
            Yii::$app->session->addFlash('success', DisposalModule::t('modules/disposal/app', 'The disposals\' approval was deleted succesfully and the disposals included in it where set back to the "Disposals for Approval" section.'));
            return $this->redirect(['index']);
        }
        catch (Exception $exc) {
            $transaction->rollBack();
            Yii::$app->session->addFlash('danger', $exc->getMessage());
            return $this->redirect(['index']);
        }        
    }

    
    
    public function actionDownload($id) {
        try {
            $approval_model = DisposalApproval::findOne(['approval_id' => $id]);

            $file = Yii::getAlias($this->module->params['disposal_exportfolder']) . $approval_model->approval_file;
            
            if (!is_readable($file)) {
                throw new Exception("The decision file cannot be found.");
            }
            
            return Yii::$app->response->SendFile($file);
            
            return $this->redirect(['/disposal/disposal-approval/index']);
        }
        catch (Exception $exc) {
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', $exc->getMessage()));
            return $this->redirect(['/disposal/disposal-approval/index']);
        }
    }
    
    /**
     * Finds the DisposalApproval model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DisposalApproval the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DisposalApproval::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
