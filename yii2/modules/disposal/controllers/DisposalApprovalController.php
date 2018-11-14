<?php

namespace app\modules\disposal\controllers;

use Exception;
use Yii;
use PhpOffice\PhpWord\TemplateProcessor;
use app\modules\base\widgets\HeadSignature\models\HeadSignature;
use app\modules\disposal\DisposalModule;
use app\modules\disposal\models\DisposalApproval;
use app\modules\disposal\models\DisposalApprovalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\base\Model;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\modules\disposal\models\Disposal;
use app\modules\disposal\models\DisposalDisposalapproval;
use app\modules\schooltransport\models\Schoolunit;
use app\modules\schooltransport\models\Directorate;
use app\modules\eduinventory\models\Teacher;
use app\models\Specialisation;
use yii\helpers\ArrayHelper;
use app\modules\disposal\models\DisposalLocaldirdecision;

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
            'access' => [   'class' => AccessControl::className(),
                'rules' =>  [
                    ['actions' => ['index', 'view', 'download'], 'allow' => true, 'roles' => ['disposal_viewer']],
                    ['actions' => ['create', 'update', 'delete', 'republish'], 'allow' => true, 'roles' => ['disposal_editor']],
                ]
            ]
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
        $model = $this->findModel($id);
        $disposal_models = $model->getDisposals()->all();
        //echo "<pre>"; print_r($disposal_models); echo "</pre>"; die();
        $teacher_models = array();
        $disposal_schools = array();
        $organic_schools = array();
        $specializations = array();
        foreach ($disposal_models as $index => $disposal_model) {
            $teacher_models[$index] = Teacher::findOne(['teacher_id' => $disposal_model['teacher_id']]);
            $disposal_schools[$index] = Schoolunit::findOne(['school_id' => $disposal_model['school_id']]);
            $organic_schools[$index] = Schoolunit::findOne(['school_id' => $teacher_models[$index]['school_id']]);
            $specializations[$index] = Specialisation::findOne(['id' => $teacher_models[$index]['specialisation_id']]);
        }

        $model->approval_file = Yii::getAlias($this->module->params['disposal_exportfolder']) . $model->approval_file;
        return $this->render('view', [
            'model' => $model,
            'disposal_models' => $disposal_models,
            'teacher_models' => $teacher_models,
            'disposal_schools' => $disposal_schools,
            'organic_schools' => $organic_schools,
            'specializations' => $specializations
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
        $use_template_with_health_reasons = false;
        //echo "<pre>"; print_r($disposal_ids);   echo "</pre>"; die();
        foreach ($disposal_ids as $index=>$disposal_id){
            $disposals_models[$index] = Disposal::find()->where(['disposal_id' => $disposal_id])->one();            
            if(!$use_template_with_health_reasons && $disposals_models[$index]->isForHealthReasons())
                $use_template_with_health_reasons = true;
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
                //echo "<pre>"; print_r($disposalapproval_models); echo "<pre>"; die();
                if(!$this->checkLocaldirdecisionUniqueness($disposalapproval_models)) 
                    throw new Exception("All disposals must belong to the same local Directorate Decision.");

                $template_filename = ($use_template_with_health_reasons) ? "DISPOSALS_APPROVAL_GENERAL_WITH_HEALTH_REASONS_TEMPLATE" : "DISPOSALS_APPROVAL_GENERAL_TEMPLATE";
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
                        throw new Exception("Failed to assign disposals to the approval.");
                    $disposal_model->archived = 1;
                    if(!$disposal_model->save())
                        throw new Exception("Failed to assign disposals to the approval.");
                    $disposalapproval_model->approval_id = $model->approval_id;
                    if(!$disposalapproval_model->save())
                        throw new Exception("Failed to assign disposals to the approval.");
                }
                if($disposals_counter == 0){
                    for($i = 0; $i < count($disposals_models); $i++)
                        $disposalapproval_models[$i]['disposal_id'] = $disposal_ids[$i];
                    throw new Exception("Please select at least one disposal.");
                }
                
                if($this->createApprovalFile($model, $disposals_models, $school_models, $teacher_models, $specialization_models, $directorate_model, $template_filename) == null)
                    throw new Exception("The creation of the approval failed, because the template file for the approval does not exist.");
                    
                $transaction->commit();
                $user = Yii::$app->user->identity->username;
                Yii::info('User ' . $user . ' ' . 'created Approval with id: '. $model->approval_id, 'disposal');

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
        $disposalapproval_models = DisposalDisposalapproval::findAll(['approval_id' => $model->approval_id]);
        $disposals_models = array();
        $school_models = array();
        $teacher_models = array();
        $specialization_models = array();
        $use_template_with_health_reasons = false;
        $disposal_ids = array();
        
        foreach ($disposalapproval_models as $index=>$disposalapproval_model) {            
            $disposals_models[$index] = Disposal::findOne(['disposal_id' => $disposalapproval_model['disposal_id']]);
            $disposal_ids[$index] = $disposalapproval_model['disposal_id'];
            if(!$use_template_with_health_reasons && $disposals_models[$index]->isForHealthReasons())
                $use_template_with_health_reasons = true;
            $school_models[$index] = $disposals_models[$index]->getSchool()->one();
            $teacher_models[$index] = $disposals_models[$index]->getTeacher()->one();
            $specialization_models[$index] = $teacher_models[$index]->getSpecialisation()->one();
        }
        $directorate_id = Schoolunit::findOne(['school_id' => $teacher_models[0]['school_id']])['directorate_id'];
        $directorate_model = Directorate::findOne(['directorate_id' => $directorate_id]);

        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            if($model->load(Yii::$app->request->post()) && Model::loadMultiple($disposalapproval_models, Yii::$app->request->post())) {
                //echo "<pre>"; print_r($disposalapproval_models); echo "</pre>"; die();
                $template_filename = ($use_template_with_health_reasons) ? "DISPOSALS_APPROVAL_GENERAL_WITH_HEALTH_REASONS_TEMPLATE" : "DISPOSALS_APPROVAL_GENERAL_TEMPLATE";
                if(!$model->save()) 
                    throw new Exception("Failed to save the changes of the approval.");
                
                $old_disposalapproval_models = DisposalDisposalapproval::findAll(['approval_id' => $model->approval_id]);
                $new_disposal_ids = array_values(ArrayHelper::map($disposalapproval_models, 'disposal_id', 'disposal_id'));
                
                $disposals_counter = 0;
                foreach ($old_disposalapproval_models as $old_disposalapproval_model) {
                    if(!in_array($old_disposalapproval_model->disposal_id, $new_disposal_ids)) {
                        $disposals_counter++;
                        if(!$old_disposalapproval_model->delete())
                            throw new Exception("Failed to save the changes of the approval.");
                        $restore_disposal_model = Disposal::findOne(['disposal_id' => $old_disposalapproval_model->disposal_id]);
                        $restore_disposal_model->archived = 0;
                        if(!$restore_disposal_model->save())
                            throw new Exception("Failed to save the changes of the approval.");
                    }
                }
                if($disposals_counter == count($old_disposalapproval_models)){//echo "<pre>"; print_r($disposals_models); echo "</pre>"; die();
                    for($i = 0; $i < count($disposals_models); $i++){
                        $disposalapproval_models[$i]['disposal_id'] = $disposal_ids[$i];
                    }
                    throw new Exception("Please select at least one disposal.");
                }

                if($this->createApprovalFile($model, $disposals_models, $school_models, $teacher_models, $specialization_models, $directorate_model, $template_filename) == null)
                    throw new Exception("The creation of the approval failed, because the template file for the approval does not exist.");
                    
                $transaction->commit();
                $user = Yii::$app->user->identity->username;
                Yii::info('User ' . $user . ' ' . 'updated Approval with id: '. $id, 'disposal');

                Yii::$app->session->addFlash('success', DisposalModule::t('modules/disposal/app', "The approval of the disposals was updated successfully."));
                return $this->redirect(['disposal-approval/index']);
            }
            else {
                return $this->render('update', [
                    'model' => $model,
                    'disposals_models' => $disposals_models,
                    'disposalapproval_models' => $disposalapproval_models,
                    'teacher_models' => $teacher_models,
                    'school_models' => $school_models,
                    'specialization_models' => $specialization_models,
                ]);
            }
        }
        catch (Exception $exc) {
            $transaction->rollBack();
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', $exc->getMessage()));
            return $this->render('update', [
                'model' => $model,
                'disposals_models' => $disposals_models,
                'disposalapproval_models' => $disposalapproval_models,
                'teacher_models' => $teacher_models,
                'school_models' => $school_models,
                'specialization_models' => $specialization_models,
            ]);
        }       
    }
    
    
    private function createApprovalFile($model, $disposals_models, $school_models, $teacher_models, $specialization_models, $directorate_model, $template_filename) 
    {
        //echo "<pre>"; print_r($teacher_models); echo "<pre>"; die();
        //echo "<pre>"; echo ($disposals_models[0]['localdirdecision_id']); echo "<pre>"; die();
        $template_path = Yii::getAlias($this->module->params['disposal_templatepath']) . $template_filename . ".docx";
        $fullpath_fileName = Yii::getAlias($this->module->params['disposal_exportfolder']) . $template_filename . '_' . $model->approval_id . ".docx";
        
        if (!file_exists($template_path)){            
            return null;
        }
        $localdirdecision_model = DisposalLocaldirdecision::find()->where(['localdirdecision_id' =>  $disposals_models[0]['localdirdecision_id']])->one();
        //echo "<pre>"; print_r($localdirdecision_model); echo "<pre>"; die();

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
        $templateProcessor->setValue('local_directorate_protocol', $localdirdecision_model->localdirdecision_protocol);
        $templateProcessor->setValue('local_directorate_decisionsubject', $localdirdecision_model->localdirdecision_subject);
        $templateProcessor->setValue('local_directorate_action', $localdirdecision_model->localdirdecision_action);
        $pyspe = !strpos(mb_strtolower($directorate_model['directorate_name'], 'UTF-8'), 'πρωτοβ') ? "ΠΥΣΠΕ " : "ΠΥΣΔΕ ";
        $pyspe .= substr(strrchr($directorate_model['directorate_name'], " "), 1);
        $templateProcessor->setValue('local_pyspe', $pyspe);
        
        $teacher_disposals = "";
        for($i = 0; $i < count($teacher_models); $i++) {
            $teacher_disposals .= "- " . $teacher_models[$i]['teacher_surname'] . " " . $teacher_models[$i]['teacher_name'] . ", εκπαιδευτικός κλάδου ";
            $teacher_disposals .= $specialization_models[$i]['code'] . ":\nδιατίθεται";
            $teacher_disposals .= ($disposals_models[$i]['disposal_hours'] == Disposal::FULL_DISPOSAL) ? " με ολική διάθεση ":
            " για " . $disposals_models[$i]['disposal_hours'] . " ώρες την εβδομάδα";
            $teacher_disposals .= " στο \"" . $school_models[$i]['school_name'] . "\"";
            $teacher_disposals .= " από " . date_format(date_create($disposals_models[$i]['disposal_startdate']), 'd-m-Y') . ' μέχρι ' . date_format(date_create($disposals_models[$i]['disposal_enddate']), 'd-m-Y');
            $teacher_disposals .= " για " . mb_strtolower($disposals_models[$i]->getDisposalreason()->one()['disposalreason_description'], 'UTF-8');
            if ($disposals_models[$i]['disposalworkobj_id'] != null)
                $teacher_disposals .= " με αντικείμενο " . mb_strtolower($disposals_models[$i]->getDisposalworkobj()->one()['disposalworkobj_description'], 'UTF-8');
            $teacher_disposals .= ".</w:t><w:br/><w:t>";
            
        }
        $templateProcessor->setValue('teacher_disposals', $teacher_disposals);
        
        $whosigns = Yii::$app->session[Yii::$app->controller->module->id . "_whosigns"];
        $templateProcessor->setValue('director_title', HeadSignature::getSigningTitle($whosigns));
        $templateProcessor->setValue('director_name', HeadSignature::getSigningName($whosigns));        
        $templateProcessor->saveAs($fullpath_fileName);
        return true;
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
            foreach ($disposal_ids as $disposal_id) {
                $disposal_model = Disposal::find()->where(['disposal_id' => $disposal_id['disposal_id']])->one();
                $disposal_model->archived = 0;
                if(!$disposal_model->save())
                    throw new Exception("The deletion of the disposals\' approval failed.");
            }
            $transaction->commit();
            /* delete old file: */
            if (file_exists(Yii::getAlias($this->module->params['disposal_exportfolder']) . $approval_model->approval_file)) {
                unlink(Yii::getAlias($this->module->params['disposal_exportfolder']) . $approval_model->approval_file);
            }

            $user = Yii::$app->user->identity->username;
            Yii::info('User ' . $user . ' ' . 'deleted Approval with id: '. $id, 'disposal');
            Yii::$app->session->addFlash('success', DisposalModule::t('modules/disposal/app', 'The disposals\' approval was deleted succesfully and the disposals included in it where set back to the "Disposals for Approval" section.'));
            return $this->redirect(['index']);
        }
        catch (Exception $exc) {
            $transaction->rollBack();
            Yii::$app->session->addFlash('danger', $exc->getMessage());
            return $this->redirect(['index']);
        }        
    }
    
    public function actionDownload($id) 
    {
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

    
    
    public function actionRepublish($id) 
    {
        $this->redirect(['disposal/index', 'archived' => 1, 'approval_id' => $id, 'republish' => 1]);
    }
    
    /**
     * Checks whether all the disposalapproval models belong to the same local Directorate Decision
     * @param DisposalDisposalapproval $disposalapproval_models
     * @return boolean
     */
    public function checkLocaldirdecisionUniqueness($disposalapproval_models) 
    {        
        if(count($disposalapproval_models) == 0)
            return false;             

        $localdirdecision_id = Disposal::findOne(['disposal_id' => $disposalapproval_models[0]['disposal_id']])['localdirdecision_id'];
        foreach ($disposalapproval_models as $disposalapproval_model){
            $tmp_disposal_model = Disposal::findOne(['disposal_id' => $disposalapproval_model['disposal_id']]);
            if(!$tmp_disposal_model && $localdirdecision_id != $tmp_disposal_model['localdirdecision_id'])
                return false;
        }

        return true;
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
