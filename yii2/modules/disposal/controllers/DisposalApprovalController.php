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
        
        $directorate_id = $school_models[0]['directorate_id'];
        foreach ($school_models as $school_model) {
            if ($school_model['directorate_id'] != $directorate_id) {
                Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', "Please select disposals of only one directorate."));
                return $this->redirect(['disposal/index']);
            }
        }


        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {            
            if($model->load(Yii::$app->request->post()) && Model::loadMultiple($disposalapproval_models, Yii::$app->request->post())) {
                
                
                //echo "<pre>"; print_r($disposalapproval_models); echo "</pre>"; die();
               $model->approval_file = '---';
               $model->approval_signedfile = '---';
                if(!$model->save()) {
                    throw new Exception("Failed to save the approval in the database");
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
                
                $template_path = yii::$app->params['disposal_templatepath'] . "DISPOSALS_APPROVAL_GENERAL_TEMPLATE.docx";
                                
                if (!file_exists(Yii::getAlias($template_path))) {
                    return null;
                }
                
                $templateProcessor = new TemplateProcessor(Yii::getAlias($template_path));                
                $templateProcessor->setValue('regionaldirect_protocoldate', $model->approval_regionaldirectprotocoldate);
                $templateProcessor->setValue('regionaldirect_protocol', $model->approval_regionaldirectprotocol);
                $templateProcessor->setValue('contactperson', Yii::$app->user->identity->surname . ' ' . Yii::$app->user->identity->name);
                $templateProcessor->setValue('postaladdress', Yii::$app->params['address']);
                $templateProcessor->setValue('phonenumber', $this->module->params['disposal_telephone']);
                $templateProcessor->setValue('fax', $this->module->params['schooltransport_fax']);
                $templateProcessor->setValue('email', Yii::$app->params['email']);
                $templateProcessor->setValue('webaddress', Yii::$app->params['web_address']);
                $templateProcessor->setValue('local_directorate', $directorate_model['directorate_name']);
                    
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
            Yii::$app->session->addFlash('success', DisposalModule::t('modules/disposal/app', 'The disposals\' approval was deleted succesfully and the disposals included in it where set back to the "Disposals for Approval" section.'));
            return $this->redirect(['index']);
        }
        catch (Exception $exc) {
            $transaction->rollBack();
            Yii::$app->session->addFlash('danger', $exc->getMessage());
            return $this->redirect(['index']);
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
