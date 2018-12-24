<?php

namespace app\modules\disposal\controllers;

use Exception;
use Yii;
use PhpOffice\PhpWord\TemplateProcessor;
use app\modules\base\widgets\HeadSignature\models\HeadSignature;
use app\modules\disposal\DisposalModule;
use app\modules\disposal\models\DisposalApproval;
use app\modules\disposal\models\DisposalApprovalSearch;
use app\modules\disposal\models\DisposalWorkobj;
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
use app\modules\disposal\models\DisposalReason;

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
                    ['actions' => ['create', 'update', 'delete', 'republish', 'archive', 'massarchive'], 'allow' => true, 'roles' => ['disposal_editor']],
                ]
            ],
        ];
    }

    /**
     * Lists all DisposalApproval models.
     * @return mixed
     */
    public function actionIndex($archived = 0)
    {
        $searchModel = new DisposalApprovalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $archived);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'archived' => $archived
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
        $teacher_models = [];
        $disposal_schools = [];
        $organic_schools = [];
        $specializations = [];
        foreach ($disposal_models as $index => $disposal_model) {
            $teacher_models[$index] = Teacher::findOne(['teacher_id' => $disposal_model['teacher_id']]);
            $disposal_schools[$index] = Schoolunit::findOne(['school_id' => $disposal_model['toschool_id']]);
            $service_schools[$index] = Schoolunit::findOne(['school_id' => $disposal_model['fromschool_id']]);
            $specializations[$index] = Specialisation::findOne(['id' => $teacher_models[$index]['specialisation_id']]);
        }

        $model->approval_file = Yii::getAlias($this->module->params['disposal_exportfolder']) . $model->approval_file;
        return $this->render('view', [
            'model' => $model,
            'disposal_models' => $disposal_models,
            'teacher_models' => $teacher_models,
            'disposal_schools' => $disposal_schools,
            'service_schools' => $service_schools,
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
        $disposal_ids = [];

        if (isset($_POST['disposal_ids'])) {
            $disposal_ids = unserialize($_POST['disposal_ids']);
        } else {
            $disposal_ids = Yii::$app->request->post('selection');
            if (count($disposal_ids) == 0) {
                Yii::$app->session->addFlash('info', DisposalModule::t('modules/disposal/app', "Please select at least one disposal."));
                return $this->redirect(['disposal/index']);
            }
        }

        $model = new DisposalApproval();
        $disposalapproval_models = [];
        $disposals_models = [];
        $teacher_models = [];
        $toschool_models = [];
        $fromschool_models = [];
        $duty_models = [];
        $reason_models = [];
        $specialization_models = [];
        $use_template_with_health_reasons = false;

        foreach ($disposal_ids as $index=>$disposal_id) {
            $disposals_models[$index] = Disposal::find()->where(['disposal_id' => $disposal_id])->one();
            if (!$use_template_with_health_reasons && $disposals_models[$index]->isForHealthReasons()) {
                $use_template_with_health_reasons = true;
            }
            $disposalapproval_models[$index] = new DisposalDisposalapproval();
            $disposalapproval_models[$index]->disposal_id = $disposal_id;
            $teacher_models[$index] = $disposals_models[$index]->getTeacher()->one();
            $fromschool_models[$index] = $disposals_models[$index]->getFromSchool()->one();
            $toschool_models[$index] = $disposals_models[$index]->getToSchool()->one();
            $reason_models[$index] = $disposals_models[$index]->getDisposalreason()->one();
            $duty_models[$index] = $disposals_models[$index]->getDisposalworkobj()->one();
            $specialization_models[$index] = $teacher_models[$index]->getSpecialisation()->one();
        }

        $directorate_id = Schoolunit::findOne(['school_id' => $disposals_models[0]['fromschool_id']])['directorate_id'];
        $directorate_model = Directorate::findOne(['directorate_id' => $directorate_id]);

        try {
            if (!$this->checkLocaldirdecisionUniqueness($disposalapproval_models)) {
                throw new Exception("All disposals must belong to the same local Directorate Decision.");
            }
        } catch (Exception $exc) {
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', $exc->getMessage()));
            return $this->redirect(['disposal/index']);
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($model->load(Yii::$app->request->post()) && Model::loadMultiple($disposalapproval_models, Yii::$app->request->post())) {
                $template_filename = ($use_template_with_health_reasons) ? "DISPOSALS_APPROVAL_GENERAL_WITH_HEALTH_REASONS_TEMPLATE" : "DISPOSALS_APPROVAL_GENERAL_TEMPLATE";
                $model->approval_file = $template_filename . '_' . $model->approval_regionaldirectprotocol . '_' . str_replace('-', '_', $model->approval_regionaldirectprotocoldate) . ".docx";
                $model->approval_signedfile = '-';
                if (!$model->save()) {
                    throw new Exception("Failed to save the approval in the database.");
                }
                $disposals_counter = 0;
                foreach ($disposalapproval_models as $disposalapproval_model) {
                    if ($disposalapproval_model->disposal_id == 0) {
                        continue;
                    }
                    $disposals_counter++;
                    $disposal_model = Disposal::findOne($disposalapproval_model->disposal_id);
                    if (!$disposal_model) {
                        throw new Exception("Failed to assign disposals to the approval.");
                    }
                    $disposal_model->archived = 1;
                    if (!$disposal_model->save()) {
                        throw new Exception("Failed to assign disposals to the approval.");
                    }
                    $disposalapproval_model->approval_id = $model->approval_id;
                    if (!$disposalapproval_model->save()) {
                        throw new Exception("Failed to assign disposals to the approval.");
                    }
                }
                if ($disposals_counter == 0) {
                    for ($i = 0; $i < count($disposals_models); $i++) {
                        $disposalapproval_models[$i]['disposal_id'] = $disposal_ids[$i];
                    }
                    throw new Exception("Please select at least one disposal.");
                }

                if ($this->createApprovalFile($model, $disposals_models, $fromschool_models, $toschool_models, $teacher_models, $specialization_models, $directorate_model, $template_filename) == null) {
                    throw new Exception("The creation of the approval failed, because the template file for the approval does not exist.");
                }

                $transaction->commit();
                $user = Yii::$app->user->identity->username;
                Yii::info('User ' . $user . ' ' . 'created Approval with id: '. $model->approval_id, 'disposal');

                Yii::$app->session->addFlash('success', DisposalModule::t('modules/disposal/app', "The approval of the disposals was created successfully."));
                return $this->redirect(['disposal-approval/index']);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'disposals_models' => $disposals_models,
                    'disposalapproval_models' => $disposalapproval_models,
                    'teacher_models' => $teacher_models,
                    'fromschool_models' => $fromschool_models,
                    'toschool_models' => $toschool_models,
                    'duty_models' => $duty_models,
                    'reason_models' => $reason_models,
                    'specialization_models' => $specialization_models,
                    'disposal_ids' => $disposal_ids,
                    'selection' => 1
                ]);
            }
        } catch (Exception $exc) {
            $transaction->rollBack();
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', $exc->getMessage()));
            return $this->render('create', [
                'model' => $model,
                'disposals_models' => $disposals_models,
                'disposalapproval_models' => $disposalapproval_models,
                'teacher_models' => $teacher_models,
                'fromschool_models' => $fromschool_models,
                'toschool_models' => $toschool_models,
                'duty_models' => $duty_models,
                'reason_models' => $reason_models,
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
        if ($model->archived == 1 || $model->deleted == 1 || !is_null(self::isRepublished($id))) {
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', "Not allowed action for that approval."));
            return $this->redirect(['disposal-approval/index']);
        }
        $disposalapproval_models = DisposalDisposalapproval::findAll(['approval_id' => $model->approval_id]);
        $disposals_models = [];
        $toschool_models = [];
        $fromschool_models = [];
        $teacher_models = [];
        $specialization_models = [];
        $duty_models = [];
        $reason_models = [];
        $use_template_with_health_reasons = false;
        $disposal_ids = [];

        foreach ($disposalapproval_models as $index=>$disposalapproval_model) {
            $disposals_models[$index] = Disposal::findOne(['disposal_id' => $disposalapproval_model['disposal_id']]);
            $disposal_ids[$index] = $disposalapproval_model['disposal_id'];
            if (!$use_template_with_health_reasons && $disposals_models[$index]->isForHealthReasons()) {
                $use_template_with_health_reasons = true;
            }
            $fromschool_models[$index] = $disposals_models[$index]->getFromSchool()->one();
            $toschool_models[$index] = $disposals_models[$index]->getToSchool()->one();
            $teacher_models[$index] = $disposals_models[$index]->getTeacher()->one();
            $reason_models[$index] = $disposals_models[$index]->getDisposalreason()->one();
            $duty_models[$index] = $disposals_models[$index]->getDisposalworkobj()->one();
            $specialization_models[$index] = $teacher_models[$index]->getSpecialisation()->one();
        }
        $directorate_id = Schoolunit::findOne(['school_id' => $disposals_models[0]['fromschool_id']])['directorate_id'];
        $directorate_model = Directorate::findOne(['directorate_id' => $directorate_id]);

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($model->load(Yii::$app->request->post()) && Model::loadMultiple($disposalapproval_models, Yii::$app->request->post())) {
                $template_filename = ($use_template_with_health_reasons) ? "DISPOSALS_APPROVAL_GENERAL_WITH_HEALTH_REASONS_TEMPLATE" : "DISPOSALS_APPROVAL_GENERAL_TEMPLATE";
                if (!$model->save()) {
                    throw new Exception("Failed to save the changes of the approval.");
                }

                $old_disposalapproval_models = DisposalDisposalapproval::findAll(['approval_id' => $model->approval_id]);
                $new_disposal_ids = array_values(ArrayHelper::map($disposalapproval_models, 'disposal_id', 'disposal_id'));

                $disposals_counter = 0;
                foreach ($old_disposalapproval_models as $old_disposalapproval_model) {
                    if (!in_array($old_disposalapproval_model->disposal_id, $new_disposal_ids, true)) {
                        $disposals_counter++;
                        if (!$old_disposalapproval_model->delete()) {
                            throw new Exception("Failed to save the changes of the approval.");
                        }
                        $restore_disposal_model = Disposal::findOne(['disposal_id' => $old_disposalapproval_model->disposal_id]);
                        $restore_disposal_model->archived = 0;
                        if (!$restore_disposal_model->save()) {
                            throw new Exception("Failed to save the changes of the approval.");
                        }
                    }
                }
                if ($disposals_counter == count($old_disposalapproval_models)) {
                    for ($i = 0; $i < count($disposals_models); $i++) {
                        $disposalapproval_models[$i]['disposal_id'] = $disposal_ids[$i];
                    }
                    throw new Exception("Please select at least one disposal.");
                }

                if ($this->createApprovalFile($model, $disposals_models, $fromschool_models, $toschool_models, $teacher_models, $specialization_models, $directorate_model, $template_filename) == null) {
                    throw new Exception("The creation of the approval failed, because the template file for the approval does not exist.");
                }

                $transaction->commit();
                $user = Yii::$app->user->identity->username;
                Yii::info('User ' . $user . ' ' . 'updated Approval with id: '. $id, 'disposal');

                Yii::$app->session->addFlash('success', DisposalModule::t('modules/disposal/app', "The approval of the disposals was updated successfully."));
                return $this->redirect(['disposal-approval/index']);
            } else {
                return $this->render('update', [
                    'model' => $model,
                    'disposals_models' => $disposals_models,
                    'disposalapproval_models' => $disposalapproval_models,
                    'teacher_models' => $teacher_models,
                    'fromschool_models' => $fromschool_models,
                    'toschool_models' => $toschool_models,
                    'duty_models' => $duty_models,
                    'reason_models' => $reason_models,
                    'specialization_models' => $specialization_models,
                ]);
            }
        } catch (Exception $exc) {
            $transaction->rollBack();
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', $exc->getMessage()));
            return $this->render('update', [
                'model' => $model,
                'disposals_models' => $disposals_models,
                'disposalapproval_models' => $disposalapproval_models,
                'teacher_models' => $teacher_models,
                'fromschool_models' => $fromschool_models,
                'toschool_models' => $toschool_models,
                'duty_models' => $duty_models,
                'reason_models' => $reason_models,
                'specialization_models' => $specialization_models,
            ]);
        }
    }


    /*
     * Returns the $id of the republish approval of an approval
     */
    private static function isRepublished($id)
    {
        return DisposalApproval::findOne(['approval_id' => $id])['approval_republished'];
    }

    private static function disposalChanged($disp1, $disp2)
    {
        if (!($disp1 instanceof Disposal) || !($disp2 instanceof Disposal)) {
            return false;
        }

        $hours_boolvalue = true;
        if (!is_null($disp1['disposal_hours']) && !is_null($disp2['disposal_hours'])) {
            $hours_boolvalue = $disp1['disposal_hours'] == $disp2['disposal_hours'];
        }

        return !($disp1['disposal_startdate'] == $disp2['disposal_startdate'] &&
                $disp1['disposal_enddate'] == $disp2['disposal_enddate'] &&
                $hours_boolvalue &&
                $disp1['disposal_days'] == $disp2['disposal_days'] &&
                $disp1['fromschool_id'] == $disp2['fromschool_id'] &&
                $disp1['toschool_id'] == $disp2['toschool_id'] &&
                $disp1['disposalreason_id'] == $disp2['disposalreason_id'] &&
                $disp1['disposalworkobj_id'] == $disp2['disposalworkobj_id']);
    }


    public function actionRepublish($id)
    {
        $approval_changed = false;
        $initialModel = $this->findModel($id);

        if ($initialModel->deleted == 1 || !is_null(self::isRepublished($id))) {
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', "Not allowed action for that approval."));
            return $this->redirect(['disposal-approval/index']);
        }

        $model = new DisposalApproval();
        $model->attributes = $initialModel->attributes;

        if ($model->archived == 1 || $model->deleted == 1) {
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', "Not allowed action for that approval."));
            return $this->redirect(['disposal-approval/index']);
        }

        $initial_disposalapproval_models = DisposalDisposalapproval::findAll(['approval_id' => $initialModel->approval_id]);

        $disposalapproval_models = [];
        foreach ($initial_disposalapproval_models as $index=>$initial_disposalapproval_model) {
            $disposalapproval_models[$index] = new DisposalDisposalapproval();
            $disposalapproval_models[$index]->disposal_id = $initial_disposalapproval_model->disposal_id;
        }

        $disposals_models = [];
        $teacher_models = [];
        $specialization_models = [];
        $use_template_with_health_reasons = false;
        $disposal_ids = [];

        $disposal_reasons = DisposalReason::find()->all();
        $disposal_duties = DisposalWorkobj::find()->all();
        $disposal_hours = Disposal::getHourOptions();
        $disposal_days = Disposal::getDayOptions();
        $schools = Schoolunit::find()->all();

        foreach ($disposalapproval_models as $index=>$disposalapproval_model) {
            $disposals_models[$index] = Disposal::findOne(['disposal_id' => $disposalapproval_model['disposal_id']]);
            $disposal_ids[$index] = $disposalapproval_model['disposal_id'];

            $fromschool_models[$index] = $disposals_models[$index]->getFromSchool()->one();
            $toschool_models[$index] = $disposals_models[$index]->getToSchool()->one();
            $teacher_models[$index] = $disposals_models[$index]->getTeacher()->one();
            $reason_models[$index] = $disposals_models[$index]->getDisposalreason()->one();
            $duty_models[$index] = $disposals_models[$index]->getDisposalworkobj()->one();
            $specialization_models[$index] = $teacher_models[$index]->getSpecialisation()->one();
        }
        $directorate_id = Schoolunit::findOne(['school_id' => $disposals_models[0]['fromschool_id']])['directorate_id'];
        $directorate_model = Directorate::findOne(['directorate_id' => $directorate_id]);

        $transaction = Yii::$app->db->beginTransaction();


        try {
            if ($model->load(Yii::$app->request->post()) && Model::loadMultiple($disposalapproval_models, Yii::$app->request->post()) && Model::loadMultiple($disposals_models, Yii::$app->request->post())) {
                $template_filename = ($use_template_with_health_reasons) ? "DISPOSALS_APPROVAL_GENERAL_WITH_HEALTH_REASONS_TEMPLATE" : "DISPOSALS_APPROVAL_GENERAL_TEMPLATE";

                $model->approval_regionaldirectprotocol = trim($model->approval_regionaldirectprotocol);
                $model->approval_regionaldirectprotocoldate = trim($model->approval_regionaldirectprotocoldate);

                if ($model->approval_regionaldirectprotocol != $initialModel->approval_regionaldirectprotocol || $model->approval_regionaldirectprotocoldate != $initialModel->approval_regionaldirectprotocoldate) {
                    $approval_changed = true;
                }

                if (!$model->save()) {
                    throw new Exception("Failed to save the changes of the approval.");
                }

                $initialModel->approval_republished = $model->approval_id;
                if (!$initialModel->save()) {
                    throw new Exception("Failed to save the changes of the approval.");
                }

                $new_disposal_ids = array_values(ArrayHelper::map($disposalapproval_models, 'disposal_id', 'disposal_id'));

                $disposals_counter = 0;
                foreach ($disposals_models as $index=>$disposal_model) {
                    if (!in_array($initial_disposalapproval_models[$index]->disposal_id, $new_disposal_ids, true)) {
                        $approval_changed = true;
                        $disposals_counter++;
                        if (!$initial_disposalapproval_models[$index]->delete()) {
                            throw new Exception("1.Failed to save the changes of the approval.");
                        }
                        $restore_disposal_model = Disposal::findOne(['disposal_id' => $initial_disposalapproval_models[$index]->disposal_id]);
                        $restore_disposal_model->archived = 0;
                        if (!$restore_disposal_model->save()) {
                            throw new Exception("2.Failed to save the changes of the approval.");
                        }
                    } else {
                        if (self::disposalChanged($disposal_model, Disposal::findOne(['disposal_id' => $disposal_model['disposal_id']]))) {
                            $approval_changed = true;
                        }
                        $republish_disposal_model = new Disposal();
                        $republish_disposal_model->attributes = $disposal_model->attributes;

                        if (!$republish_disposal_model->save()) {
                            throw new Exception("3.Failed to save the changes of the approval.");
                        }

                        $disposal_model = Disposal::findOne(['disposal_id' => $disposal_model->disposal_id]); /* Reset changes */
                        $disposal_model->disposal_republished = $republish_disposal_model->disposal_id;
                        $disposal_model->deleted = 1;

                        if (!$disposal_model->save()) {
                            throw new Exception("3.Failed to save the changes of the approval.");
                        }

                        $republish_disposalapproval_model = new DisposalDisposalapproval();
                        $republish_disposalapproval_model->approval_id = $model->approval_id;
                        $republish_disposalapproval_model->disposal_id = $republish_disposal_model->disposal_id;

                        if (!$republish_disposalapproval_model->save()) {
                            throw new Exception("4.Failed to save the changes of the approval.");
                        }
                    }
                }

                if (!$approval_changed) {
                    throw new Exception("Error: There should be at least one change in relation to the initial Approval.");
                }

                if ($disposals_counter == count($initial_disposalapproval_models)) {
                    for ($i = 0; $i < count($disposals_models); $i++) {
                        $disposalapproval_models[$i]['disposal_id'] = $disposal_ids[$i];
                    }
                    throw new Exception("Please select at least one disposal.");
                }


                unset($fromschool_models, $toschool_models, $teacher_models, $reason_models, $duty_models, $specialization_models, $disposals_models_republish);
                $fromschool_models = [];
                $toschool_models = [];
                $teacher_models = [];
                $reason_models = [];
                $duty_models = [];
                $specialization_models = [];
                $disposals_models_republish = [];
                $disposalapproval_models_republish = DisposalDisposalapproval::findAll(['approval_id' => $model->approval_id]);

                foreach ($disposalapproval_models_republish as $index=>$disposalapproval_model_republish) {
                    $disposals_models_republish[$index] = Disposal::findOne(['disposal_id' => $disposalapproval_model_republish->disposal_id]);
                    if (!$use_template_with_health_reasons && $disposals_models_republish[$index]->isForHealthReasons()) {
                        $use_template_with_health_reasons = true;
                    }
                    $fromschool_models[$index] = $disposals_models_republish[$index]->getFromSchool()->one();
                    $toschool_models[$index] = $disposals_models_republish[$index]->getToSchool()->one();
                    $teacher_models[$index] = $disposals_models_republish[$index]->getTeacher()->one();
                    $reason_models[$index] = $disposals_models_republish[$index]->getDisposalreason()->one();
                    $duty_models[$index] = $disposals_models_republish[$index]->getDisposalworkobj()->one();
                    $specialization_models[$index] = $teacher_models[$index]->getSpecialisation()->one();
                }

                if ($this->createApprovalFile($model, $disposals_models_republish, $fromschool_models, $toschool_models, $teacher_models, $specialization_models, $directorate_model, $template_filename) == null) {
                    throw new Exception("The creation of the approval failed, because the template file for the approval does not exist.");
                }

                $transaction->commit();
                $user = Yii::$app->user->identity->username;
                Yii::info('User ' . $user . ' ' . 'updated Approval with id: '. $id, 'disposal');

                Yii::$app->session->addFlash('success', DisposalModule::t('modules/disposal/app', "The approval of the disposals was updated successfully."));
                return $this->redirect(['disposal-approval/index']);
            } else {
                return $this->render('republish', [
                    'model' => $model,
                    'disposals_models' => $disposals_models,
                    'disposalapproval_models' => $disposalapproval_models,
                    'teacher_models' => $teacher_models,
                    'specialization_models' => $specialization_models,

                    'disposal_hours' => $disposal_hours,
                    'disposal_days' => $disposal_days,
                    'disposal_reasons' => $disposal_reasons,
                    'disposal_duties' => $disposal_duties,
                    'schools' => $schools
                ]);
            }
        } catch (Exception $exc) {
            $transaction->rollBack();
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', $exc->getMessage()));
            return $this->render('republish', [
                'model' => $model,
                'disposals_models' => $disposals_models,
                'disposalapproval_models' => $disposalapproval_models,
                'teacher_models' => $teacher_models,
                'specialization_models' => $specialization_models,

                'disposal_hours' => $disposal_hours,
                'disposal_days' => $disposal_days,
                'disposal_reasons' => $disposal_reasons,
                'disposal_duties' => $disposal_duties,
                'schools' => $schools
            ]);
        }
    }


    private function createApprovalFile($model, $disposals_models, $fromschool_models, $toschool_models, $teacher_models, $specialization_models, $directorate_model, $template_filename)
    {
        $template_path = Yii::getAlias($this->module->params['disposal_templatepath']) . $template_filename . ".docx";
        $fullpath_fileName = Yii::getAlias($this->module->params['disposal_exportfolder']) . $template_filename . '_' . $model->approval_id . ".docx";

        if (!file_exists($template_path)) {
            return null;
        }

        $actions = [];
        $protocol = $disposals_models[0]->getLocaldirdecision()->one()['localdirdecision_action'];
        $subject = $disposals_models[0]->getLocaldirdecision()->one()['localdirdecision_subject'];
        foreach ($disposals_models as $index=>$disposal_model) {
            $actions[$index] = $disposal_model->getLocaldirdecision()->one()['localdirdecision_action'];
        }
        $document_action = "";
        $actions = array_unique($actions);
        $actions = array_values($actions);
        for ($i = 0; $i < count($actions); $i++) {
            $document_action .= $actions[$i];

            if (count($actions) >= 2 && $i == count($actions) - 2) {
                $document_action .= " και ";
            } elseif ($i != count($actions) - 1) {
                $document_action .= ', ';
            }
        }
        if (count($actions) > 1) {
            $document_action = 'τις αριθμ. ' . $document_action . ' Πράξεις';
        } else {
            $document_action = 'την αριθμ. ' . $document_action . ' Πράξη';
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
        $templateProcessor->setValue('local_directorate_protocol', $protocol);// $localdirdecision_model->localdirdecision_protocol);
        $templateProcessor->setValue('local_directorate_decisionsubject', $subject); //$localdirdecision_model->localdirdecision_subject);
        $templateProcessor->setValue('local_directorate_action', $document_action); //$localdirdecision_model->localdirdecision_action);
        $pyspe = ($directorate_model['directorate_stage'] == 'PRIMARY') ? "ΠΥΣΠΕ " : "ΠΥΣΔΕ ";
        $pyspe .= substr(strrchr($directorate_model['directorate_name'], " "), 1);
        $templateProcessor->setValue('local_pyspe', $pyspe);

        $teacher_disposals = "";
        for ($i = 0; $i < count($teacher_models); $i++) {
            $teacher_disposals .= (string)($i+1) . ") " . $teacher_models[$i]['teacher_surname'] . " " . $teacher_models[$i]['teacher_name'] . ", εκπαιδευτικός κλάδου ";
            $teacher_disposals .= $specialization_models[$i]['code'] . ":\nδιατίθεται από το \"" . $fromschool_models[$i]['school_name'] . "\"";

            $hours_word = (!is_null($disposals_models[$i]['disposal_hours']) && $disposals_models[$i]['disposal_hours'] == 1) ? " ώρα" : " ώρες";
            $days_word = (!is_null($disposals_models[$i]['disposal_days']) && $disposals_models[$i]['disposal_days'] == 1) ? " ημέρα " : " ημέρες ";

            if ($disposals_models[$i]['disposal_days'] == Disposal::FULL_DISPOSAL) {
                $teacher_disposals .= " με ολική διάθεση ";
            } elseif (!is_null($disposals_models[$i]['disposal_hours'])) {
                $teacher_disposals .= " για " . $disposals_models[$i]['disposal_days'] . $days_word . "την εβδομάδα (" . $disposals_models[$i]['disposal_days'] . $hours_word . ")";
            } else {
                $teacher_disposals .= " για " . $disposals_models[$i]['disposal_days'] . $days_word . "την εβδομάδα";
            }

            $teacher_disposals .= " στο \"" . $toschool_models[$i]['school_name'] . "\"";
            $teacher_disposals .= " από " . date_format(date_create($disposals_models[$i]['disposal_startdate']), 'd-m-Y') . ' μέχρι ' . date_format(date_create($disposals_models[$i]['disposal_enddate']), 'd-m-Y');
            $teacher_disposals .= " για " . mb_strtolower($disposals_models[$i]->getDisposalreason()->one()['disposalreason_description'], 'UTF-8');
            $teacher_disposals .= " με αντικείμενο " . mb_strtolower($disposals_models[$i]->getDisposalworkobj()->one()['disposalworkobj_description'], 'UTF-8');
            $teacher_disposals .= ".</w:t><w:br/><w:br/><w:t>";
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
            if ($approval_model->archived == 1 || $approval_model->deleted == 1 || !is_null(self::isRepublished($id))) {
                throw new Exception('Not allowed action for that approval.');
            }

            $approval_model->deleted = 1;
            if (!$approval_model->save()) {
                throw new Exception("The deletion of the disposals\' approval failed.");
            }
            /* If it is a republish:
             * - find the last Approval that has been republished and set approval_republished as null
             * - soft delete each disposal of the republication Approval             *
             * - undelete each disposal of the initial Approval
             * */
            $initial_approval = DisposalApproval::findOne(['approval_republished' => $id]);
            if (!is_null($initial_approval)) {
                $initial_approval->approval_republished = null;
                if (!$initial_approval->save()) {
                    throw new Exception('The deletion of the disposals\' approval failed.');
                }

                $disposal_ids = DisposalDisposalapproval::findAll(['approval_id' => $approval_model->approval_id]);
                foreach ($disposal_ids as $disposal_id) {
                    $republish_disposal = Disposal::findOne(['disposal_id' => $disposal_id->disposal_id]);
                    $republish_disposal->deleted = 1;
                    if (!$republish_disposal->save()) {
                        throw new Exception('The deletion of the disposals\' approval failed.');
                    }
                }
                unset($disposal_ids);
                $disposal_ids = DisposalDisposalapproval::findAll(['approval_id' => $initial_approval->approval_id]);
                foreach ($disposal_ids as $disposal_id) {
                    $disposal_model = Disposal::find()->where(['disposal_id' => $disposal_id['disposal_id']])->one();
                    $disposal_model->deleted = 0;
                    $disposal_model->disposal_rejected = 0;
                    $disposal_model->disposal_republished = null;

                    if (!$disposal_model->save()) {
                        throw new Exception("The deletion of the disposals\' approval failed.");
                    }
                }
            } else {
                $disposal_ids = DisposalDisposalapproval::findAll(['approval_id' => $approval_model->approval_id]);
                foreach ($disposal_ids as $disposal_id) {
                    $disposal_model = Disposal::find()->where(['disposal_id' => $disposal_id['disposal_id']])->one();
                    $disposal_model->archived = 0;
                    if (!$disposal_model->save()) {
                        throw new Exception("The deletion of the disposals\' approval failed.");
                    }
                }
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
        } catch (Exception $exc) {
            $transaction->rollBack();
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', $exc->getMessage()));
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
        } catch (Exception $exc) {
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', $exc->getMessage()));
            return $this->redirect(['/disposal/disposal-approval/index']);
        }
    }


    /**
     * Checks whether all the disposalapproval models belong to the same local Directorate Decision
     * @param DisposalDisposalapproval $disposalapproval_models
     * @return boolean
     */
    public function checkLocaldirdecisionUniqueness($disposalapproval_models)
    {
        if (count($disposalapproval_models) == 0) {
            return false;
        }

        $localdirdecision = Disposal::findOne(['disposal_id' => $disposalapproval_models[0]['disposal_id']])->getLocaldirdecision()->one();
        foreach ($disposalapproval_models as $disposalapproval_model) {
            $tmp_disposal_model = Disposal::findOne(['disposal_id' => $disposalapproval_model['disposal_id']])->getLocaldirdecision()->one();
            if ($localdirdecision['localdirdecision_protocol'] != $tmp_disposal_model['localdirdecision_protocol'] || $localdirdecision['directorate_id'] != $tmp_disposal_model['directorate_id']) {
                return false;
            }
        }
        return true;
    }


    public function actionMassarchive($archive = 1)
    {
        $approval_ids = Yii::$app->request->post('selection');

        if (count($approval_ids) == 0) {
            Yii::$app->session->addFlash('info', DisposalModule::t('modules/disposal/app', "Please select at least one approval."));
            return $this->redirect(['disposal-approval/index', 'archived' => $archive]);
        }

        $success_message = ($archive) ? 'The archive was completed successfully.' : 'The restoration was completed successfully.';

        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($approval_ids as $approval_id) {
                $this->archive($approval_id, $archive);
            }

            $transaction->commit();
            Yii::$app->session->addFlash('success', DisposalModule::t('modules/disposal/app', $success_message));
            return $this->redirect(['disposal-approval/index', 'archived' => $archive]);
        } catch (Exception $exc) {
            $transaction->rollBack();
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', $exc->getMessage()));
            return $this->redirect(['disposal-approval/index', 'archived' => $archive]);
        }
    }


    public function actionArchive($id, $archive = 1)
    {
        $success_message = ($archive) ? 'The archive was completed succesfully.' : 'The restoration was completed successfully.';
        try {
            $this->archive($id, $archive);

            Yii::$app->session->addFlash('success', DisposalModule::t('modules/disposal/app', 'The disposals\' approval was archived succesfully.'));
            return $this->redirect(['index', 'archive' => $archive]);
        } catch (Exception $exc) {
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', $exc->getMessage()));
            return $this->redirect(['index', 'archive' => $archive]);
        }
    }

    private function archive($id, $archive)
    {
        $already_archrest_message = ($archive) ? 'The selected decision is alreary archived.' : 'The selected decision is not archived.';
        $fail_message = ($archive) ? 'Error in archiving the decision.' : 'Error in restoring the decision.';
        $action_message = ($archive) ? 'archived' : 'restored.';

        $model = $this->findModel($id);
        if ($model->archived == $archive) {
            throw new Exception($already_archrest_message);
        }
        $model->archived = $archive;
        if (!$model->save()) {
            throw new Exception($fail_message);
        }

        $user = Yii::$app->user->identity->username;
        Yii::info('User ' . $user . ' '  . $action_message . ' Approval with id: '. $id, 'disposal');
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
