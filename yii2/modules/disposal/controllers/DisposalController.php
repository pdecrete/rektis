<?php

namespace app\modules\disposal\controllers;

use DateTime;
use Yii;
use app\modules\base\models\FileImport;
use app\modules\disposal\models\Disposal;
use app\modules\disposal\models\DisposalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\modules\disposal\DisposalModule;
use app\models\Specialisation;
use app\modules\eduinventory\models\Teacher;
use app\modules\schooltransport\models\Schoolunit;
use app\modules\schooltransport\models\Statistic;
use app\modules\disposal\models\DisposalReason;
use app\modules\disposal\models\DisposalWorkobj;
use yii\helpers\Json;
use app\modules\disposal\models\DisposalLocaldirdecision;
use app\modules\schooltransport\models\Directorate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Shared\Date;

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
            'access' => [   'class' => AccessControl::className(),
                'rules' =>  [
                    ['actions' => ['getteacher-ajax', 'getlocaldirdecision-ajax', 'index', 'view'], 'allow' => true, 'roles' => ['disposal_viewer']],
                    ['actions' => ['create', 'update', 'delete', 'massdelete' ,'importdisposals', 'reject', 'restore'], 'allow' => true, 'roles' => ['disposal_editor']],
                ]
            ]
        ];
    }


    public function actionGetlocaldirdecisionAjax()
    {
        $protocol = null;
        $directorate = null;
        $data = null;
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $protocol = Yii::$app->request->post('localdirdecision_protocol');
            $directorate = Yii::$app->request->post('localdirdecision_directorate');//->andWhere(['localdirdecision_directorate' => $directorate])
            //$action = Yii::$app->request->post('localdirdecision_action');
            $localdir_decision = DisposalLocaldirdecision::find()->where(['localdirdecision_protocol' => $protocol])->andWhere(['directorate_id' => $directorate])->one();//->andWhere(['localdirdecision_action' => $action])->one();
            $data = $localdir_decision;
        }

        return Json::encode($data);
    }

    public function actionGetteacherAjax()
    {
        $data = null;
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $searchBy = Yii::$app->request->post('idType');
            $data = Yii::$app->request->post('id');
            if ($searchBy == "regnumber") {
                $teacher = Teacher::findOne(['teacher_registrynumber' => $data]);
            } elseif ($searchBy == "vat") {
                $teacher = Teacher::findOne(['teacher_afm' => $data]);
            }
            $data = $teacher;
            //echo "<pre>"; print_r($teacher); echo "</pre>"; die();
        }

        return Json::encode($data);
    }

    /**
     * Lists all Disposal models.
     * @return mixed
     */
    public function actionIndex($archived = 0, $approval_id = -1, $rejected = 0)
    {
        if (!is_numeric($archived) || ($archived != 0 && $archived != 1)) {
            $archived = 0;
        }

        if (!is_numeric($approval_id) || !is_numeric($rejected)) {
            $approval_id = -1;
            $rejected = 0;
        }

        $disposal_reasons = DisposalReason::find()->all();
        $specialisations = Specialisation::find()->select('code')->all();
        $directorates = Directorate::find()->select('directorate_shortname')->all();
        $decisions = DisposalLocaldirdecision::find();
        $decisions_protocols = $decisions->select('localdirdecision_protocol')->all();
        $decisions_actions = $decisions->select('localdirdecision_action')->all();

        $searchModel = new DisposalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $archived, $approval_id, $rejected);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'archived' => $archived,
            'rejected' => $rejected,
            'disposal_reasons' => $disposal_reasons,
            'specialisations' => $specialisations,
            'directorates' => $directorates,
            'decisions_protocols' => $decisions_protocols,
            'decisions_actions' => $decisions_actions
        ]);
    }

    /**
     * Displays a single Disposal model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $teacher = $model->getTeacher()->one();
        $specialisation = Specialisation::findOne(['id' => $teacher['specialisation_id']]);
        $organicpost = Schoolunit::findOne(['school_id' => $teacher['school_id']]);
        $disposal_school = $model->getToSchool()->one();
        $service_school = $model->getFromSchool()->one();
        $disposal_reason = $model->getDisposalreason()->one();
        $disposal_workobj = $model->getDisposalworkobj()->one();
        $array_model = $model->toArray();

        if ($array_model['disposal_hours'] == Disposal::FULL_DISPOSAL) {
            $array_model['disposal_hours'] = 'Ολική Διάθεση';
        }
        if ($array_model['disposal_days'] == Disposal::FULL_DISPOSAL) {
            $array_model['disposal_days'] = 'Ολική Διάθεση';
        }
        $array_model['disposal_startdate'] = date_format(date_create($model['disposal_startdate']), 'd/m/Y');
        $array_model['disposal_enddate'] = date_format(date_create($model['disposal_enddate']), 'd/m/Y');
        $array_model['teacher_id'] = $teacher['teacher_surname'] . ' ' . $teacher['teacher_name'] . ' (' . $specialisation['code'] . ', ' . $specialisation['name'] . ')';
        $array_model['fromschool_id'] = $service_school['school_name'];
        $array_model['toschool_id'] = $disposal_school['school_name'];
        $array_model['Organic Post'] = $organicpost->school_name;
        $array_model['disposalreason_id'] = $disposal_reason['disposalreason_description'];
        $array_model['disposalworkobj_id'] = $disposal_workobj['disposalworkobj_description'];

        return $this->render('view', [
            'model' => $array_model
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
            $localdirdecision_model = new DisposalLocaldirdecision();
            $schools = Schoolunit::find()->all();
            $specialisations = Specialisation::find()->all();
            $disposal_reasons = DisposalReason::find()->all();
            $disposal_workobjs = DisposalWorkobj::find()->all();
            $directorates = Directorate::find()->orderBy('directorate_name')->all();
            $model = new Disposal();

            $disposal_hours = Disposal::getHourOptions();
            $disposal_days = Disposal::getDayOptions();

            if ($model->load(Yii::$app->request->post()) && $teacher_model->load(Yii::$app->request->post()) && $localdirdecision_model->load(Yii::$app->request->post())) {
                if (empty($model->disposal_days)) {
                    $model->disposal_days = 0;
                }
                if (empty($model->disposal_hours)) {
                    $model->disposal_hours = 0;
                }
                $localdirdecision_model->localdirdecision_action = trim($localdirdecision_model->localdirdecision_action);
                $localdirdecision_model->localdirdecision_protocol = trim($localdirdecision_model->localdirdecision_protocol);

                $this->checkDaysHoursValidity($model);

                /* if ($model->toschool_id == $model->fromschool_id) {
                    throw new Exception("The school of the disposal must be different to the school of the service position of the teacher");
                } */

                $existing_teacher_model = Teacher::findOne(['teacher_afm' => $teacher_model->teacher_afm]);

                if (is_null($existing_teacher_model)) {
                    throw new Exception("There is no teacher with such VAT number. Please update the teachers database.");
                //if(!$teacher_model->save()) {
                    //    throw new Exception("Error in saving the teacher details in the database.");
                    //}
                    //$existing_teacher_model->setAttributes($teacher_model->attributes);
                    //$model->teacher_id = $teacher_model->teacher_id;
                } else {
                    $model->teacher_id = $existing_teacher_model->teacher_id;
                    $teacher_model->setAttributes($existing_teacher_model->attributes);
                }

                if ($model->disposal_enddate == "") {
                    $model->disposal_endofteachingyear_flag = 1;
                }

                if ($model->disposal_endofteachingyear_flag == 1) {
                    $school_model = Schoolunit::findOne(['school_id' => $model->fromschool_id]);
                    if ($school_model->getSchoolStage() == 'PRIMARY') {
                        $timestamp = strtotime($this->module->params['teachyear_enddate_primary'] . '-' .
                            (Statistic::getSchoolYearOf(DateTime::createFromFormat("Y-m-d", $model->disposal_startdate)) + 1));
                        $model->disposal_enddate = date("Y-m-d", $timestamp);
                    } elseif ($school_model->getSchoolStage() == 'SECONDARY') {
                        $timestamp = strtotime($this->module->params['teachyear_enddate_secondary'] . '-' .
                            (Statistic::getSchoolYearOf(DateTime::createFromFormat("Y-m-d", $model->disposal_startdate)) + 1));
                        $model->disposal_enddate = date("Y-m-d", $timestamp);
                    }
                }

                if ($model->disposal_enddate <= $model->disposal_startdate) {
                    throw new Exception("The start date of the disposal must be earlier its end date.");
                }

                $existing_localdirdecision_model = DisposalLocaldirdecision::find()
                                                    ->where(['like', 'localdirdecision_protocol', $localdirdecision_model->localdirdecision_protocol])
                                                    ->andWhere(['like', 'localdirdecision_action', $localdirdecision_model->localdirdecision_action])
                                                    ->andWhere(['directorate_id' => $localdirdecision_model->directorate_id])->one();
                if (is_null($existing_localdirdecision_model)) {
                    if (!$localdirdecision_model->save()) {
                        throw new Exception("Error in saving the teacher details in the database.");
                    }
                    $model->localdirdecision_id = $localdirdecision_model->localdirdecision_id;
                } else {
                    $model->localdirdecision_id = $existing_localdirdecision_model->localdirdecision_id;
                }

                if (!$model->save()) {
                    //echo "<pre>"; print_r($model); echo "<pre>"; die();
                    throw new Exception("Error in saving the disposal details in the database.");
                }

                $transaction->commit();
                $user = Yii::$app->user->identity->username;
                Yii::info('User ' . $user . ' ' . 'created Disposal with id: '. $model->disposal_id, 'disposal');
                Yii::$app->session->addFlash('success', DisposalModule::t('modules/disposal/app', "The teacher disposal was saved successfully."));
                return $this->redirect(['index']);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'teacher_model' => $teacher_model,
                    'localdirdecision_model' => $localdirdecision_model,
                    'schools' => $schools,
                    'disposal_hours' => $disposal_hours,
                    'disposal_days' => $disposal_days,
                    'specialisations' => $specialisations,
                    'disposal_reasons' => $disposal_reasons,
                    'disposal_workobjs' => $disposal_workobjs,
                    'directorates' => $directorates
                ]);
            }
        } catch (Exception $exc) {
            $transaction->rollBack();
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', $exc->getMessage()));
            return $this->render('create', [
                'model' => $model,
                'teacher_model' => $teacher_model,
                'localdirdecision_model' => $localdirdecision_model,
                'schools' => $schools,
                'disposal_hours' => $disposal_hours,
                'disposal_days' => $disposal_days,
                'specialisations' => $specialisations,
                'disposal_reasons' => $disposal_reasons,
                'disposal_workobjs' => $disposal_workobjs,
                'directorates' => $directorates

            ]);
        }
    }


    private function checkDaysHoursValidity($model)
    {
        if (($model->disposal_hours == -1 && $model->disposal_days != -1)
            || (($model->disposal_hours != -1 && $model->disposal_hours != 0) && $model->disposal_days == -1)
            || ($model->disposal_days == 5 && $model->disposal_hours == 0)
            ) {
            throw new Exception('Error: Please check the validity of "days" and "hours" of the disposal.');
        } elseif ($model->disposal_days == -1) {
            $model->disposal_hours = -1;
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

            if ($model->deleted == 1 || $model->archived == 1 || $model->disposal_rejected == 1) {
                Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', "The disposal is not allowed to be updated."));
                return $this->redirect(['index']);
            }
            $localdirdecision_model = DisposalLocaldirdecision::findOne(['localdirdecision_id' => $model->localdirdecision_id]);
            $teacher_model = Teacher::findOne(['teacher_id' => $model->teacher_id]);
            $schools = Schoolunit::find()->all();
            $specialisations = Specialisation::find()->all();
            $disposal_reasons = DisposalReason::find()->all();
            $disposal_workobjs = DisposalWorkobj::find()->all();
            $directorates = Directorate::find()->orderBy('directorate_name')->all();

            $disposal_hours = Disposal::getHourOptions();
            $disposal_days = Disposal::getDayOptions();

            if ($model->load(Yii::$app->request->post())) {
                $transaction = Yii::$app->db->beginTransaction();


                $this->checkDaysHoursValidity($model);
                /*                 if ($model->fromschool_id == $model->toschool_id) {
                                    throw new Exception("The school of the disposal must be different to the school of the service position of the teacher");
                                } */

                if ($model->disposal_enddate == "") {
                    $model->disposal_endofteachingyear_flag = 1;
                }

                if ($model->disposal_endofteachingyear_flag == 1) {
                    $school_model = Schoolunit::findOne(['school_id' => $model->fromschool_id]);
                    if ($school_model->getSchoolStage() == 'PRIMARY') {
                        $timestamp = strtotime($this->module->params['teachyear_enddate_primary'] . '-' .
                            (Statistic::getSchoolYearOf(DateTime::createFromFormat("Y-m-d", $model->disposal_startdate)) + 1));
                        $model->disposal_enddate = date("Y-m-d", $timestamp);
                    } elseif ($school_model->getSchoolStage() == 'SECONDARY') {
                        $timestamp = strtotime($this->module->params['teachyear_enddate_secondary'] . '-' .
                            (Statistic::getSchoolYearOf(DateTime::createFromFormat("Y-m-d", $model->disposal_startdate)) + 1));
                        $model->disposal_enddate = date("Y-m-d", $timestamp);
                    }
                }

                if ($model->disposal_enddate <= $model->disposal_startdate) {
                    throw new Exception("The start date of the disposal must be earlier its end date.");
                }

                if (!$teacher_model->save()) {
                    throw new Exception("Error in saving the teacher details in the database.");
                }
                $model->teacher_id = $teacher_model->teacher_id;

                if (!$model->save()) {
                    throw new Exception("Error in saving the disposal details in the database.");
                }
                /* This should be placed in the code part that finalizes the approval of the disposal by PDE
                $ledger_model = new DisposalLedger();
                $ledger_model->setAttributes($model->attributes);
                if(!$ledger_model->save()){
                    throw new Exception("Error in completing transaction for disposal save.");
                }*/

                $transaction->commit();
                $user = Yii::$app->user->identity->username;
                Yii::info('User ' . $user . ' ' . 'updated Disposal with id: '. $model->disposal_id, 'disposal');
                Yii::$app->session->addFlash('success', DisposalModule::t('modules/disposal/app', "The teacher disposal was saved successfully."));
                return $this->redirect(['index']);
            } else {//echo "<pre>"; print_r($teacher_model->load(Yii::$app->request->post())); echo "</pre>"; die();
                return $this->render('update', [
                    'model' => $model,
                    'teacher_model' => $teacher_model,
                    'localdirdecision_model' => $localdirdecision_model,
                    'schools' => $schools,
                    'disposal_hours' => $disposal_hours,
                    'disposal_days' => $disposal_days,
                    'specialisations' => $specialisations,
                    'disposal_reasons' => $disposal_reasons,
                    'disposal_workobjs' => $disposal_workobjs,
                    'directorates' => $directorates
                ]);
            }
        } catch (Exception $exc) {
            $transaction->rollBack();
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', $exc->getMessage()));
            return $this->render('update', [
                'model' => $model,
                'teacher_model' => $teacher_model,
                'localdirdecision_model' => $localdirdecision_model,
                'schools' => $schools,
                'disposal_hours' => $disposal_hours,
                'disposal_days' => $disposal_days,
                'specialisations' => $specialisations,
                'disposal_reasons' => $disposal_reasons,
                'disposal_workobjs' => $disposal_workobjs,
                'directorates' => $directorates
            ]);
        }
    }


    public function actionMassdelete()
    {
        $disposal_ids = Yii::$app->request->post('selection');
        if (count($disposal_ids) == 0) {
            Yii::$app->session->addFlash('info', DisposalModule::t('modules/disposal/app', "Please select at least one disposal."));
            return $this->redirect(['disposal/index']);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($disposal_ids as $disposal_id) {
                $this->delete($disposal_id);
            }

            $transaction->commit();
            Yii::$app->session->addFlash('success', DisposalModule::t('modules/disposal/app', "The deletion was completed successfully."));
            return $this->redirect(['index']);
        } catch (Exception $exc) {
            $transaction->rollBack();
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', "The deletion failed."));
            return $this->redirect(['index']);
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
            $this->delete($id);

            Yii::$app->session->addFlash('success', DisposalModule::t('modules/disposal/app', "The teacher disposal was deleted successfully."));
            return $this->redirect(['index']);
        } catch (Exception $exc) {
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', "The deletion failed."));
            return $this->redirect(['index']);
        }
    }

    private function delete($id)
    {
        $model = $this->findModel($id);
        if ($model->archived == 1 || $model->disposal_rejected == 1) {
            throw new Exception();
        }
        $model->deleted = 1;
        if (!$model->save()) {
            throw new Exception();
        }

        $user = Yii::$app->user->identity->username;
        Yii::info('User ' . $user . ' ' . 'deleted Disposal with id: '. $model->disposal_id, 'disposal');
    }

    /**
     * Restores an existing Disposal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionRestore($id)
    {
        try {
            $this->restore($id);

            Yii::$app->session->addFlash('success', DisposalModule::t('modules/disposal/app', "The teacher disposal was restored successfully."));
            return $this->redirect(['index']);
        } catch (Exception $exc) {
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', "The teacher disposal restoration failed."));
            return $this->redirect(['index']);
        }
    }

    private function restore($id)
    {
        $model = $this->findModel($id);
        if ($model->archived == 1 || ($model->archived == 0 && $model->disposal_rejected == 0) || $model->deleted == 1) {
            throw new Exception();
        }
        $model->disposal_rejected = 0;
        if (!$model->save()) {
            throw new Exception();
        }

        $user = Yii::$app->user->identity->username;
        Yii::info('User ' . $user . ' ' . 'restored Disposal with id: '. $model->disposal_id, 'disposal');
    }

    /**
     * Rejects a Disposal.
     * If rejection is successful, the disposal will be moved to the rejected disposals page.
     * @param integer $id
     * @return mixed
     */
    public function actionReject($id)
    {
        try {
            $this->reject($id);

            Yii::$app->session->addFlash('success', DisposalModule::t('modules/disposal/app', "The teacher disposal was rejected successfully and moved to the Rejected Disposals. You can still restore the disposal from the Rejected Disposals state."));
            return $this->redirect(['index']);
        } catch (Exception $exc) {
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', "The teacher disposal rejection failed."));
            return $this->redirect(['index']);
        }
    }


    private function reject($id)
    {
        $model = $this->findModel($id);
        if ($model->archived == 1 || $model->disposal_rejected == 1 || $model->deleted == 1) {
            throw new Exception();
        }
        $model->disposal_rejected = 1;
        if (!$model->save()) {
            throw new Exception();
        }

        $user = Yii::$app->user->identity->username;
        Yii::info('User ' . $user . ' ' . 'rejected Disposal with id: '. $model->disposal_id, 'disposal');
    }


    public function actionImportdisposals()
    {
        $cells = ['DIRECTORATE' => 'C3', 'PROTOCOL' => 'C4', 'ACTION' => 'C5', 'SUBJECT' => 'C6'];
        $disposals_columns = [  'AM' => 2, 'SURNAME' => 3, 'NAME' => 4, 'SPECIALISATION' => 5, 'SERVICE_SCHOOL' => 6, 'DISPOSAL_SCHOOL' => 7,
                                'HOURS' => 8, 'DAYS' => 9, 'START_DATE' => 10, 'END_DATE' => 11, 'DISPOSAL_REASON' => 12, 'DISPOSAL_DUTY' => 13];
        $base_disposalsdata_row = 9;
        try {
            $directorate = '';
            $transaction = Yii::$app->db->beginTransaction();
            $import_model = new FileImport();

            if ($import_model->load(Yii::$app->request->post())) {
                $import_model->upload($this->module->params['disposal_importfolder']);
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(Yii::getAlias(Yii::$app->controller->module->params['disposal_importfolder']) . $import_model->importfile);
                if (!$spreadsheet) {
                    throw new Exception("(@import)");
                }

                $disposals_worksheet = $spreadsheet->getSheetByName('Διαθέσεις');

                $directorate = $disposals_worksheet->getCell($cells['DIRECTORATE'])->getFormattedValue();
                $protocol = (string)$disposals_worksheet->getCell($cells['PROTOCOL'])->getFormattedValue();
                $action = (string)$disposals_worksheet->getCell($cells['ACTION'])->getFormattedValue();
                $subject = (string)$disposals_worksheet->getCell($cells['SUBJECT'])->getFormattedValue();
                $rowiterator = $disposals_worksheet->getRowIterator($base_disposalsdata_row, null);
                $localdir_id = self::getDirectorateId($directorate);

                $localdir_dec = DisposalLocaldirdecision::find()->where(['localdirdecision_protocol' => $protocol])->andWhere(['directorate_id' => $localdir_id])->one();
                if ($localdir_dec) {
                    Yii::$app->session->addFlash('info', DisposalModule::t('modules/disposal/app', "The local directorate decision already exists and not change was applied to it."));
                } else {
                    $localdir_dec = new DisposalLocaldirdecision();
                    $localdir_dec->localdirdecision_protocol = $protocol;
                    $localdir_dec->localdirdecision_action = $action;
                    $localdir_dec->localdirdecision_subject = $subject;
                    $localdir_dec->directorate_id = $localdir_id;
                    $localdir_dec->deleted = 0;
                    $localdir_dec->archived = 0;
                    if (!$localdir_dec->save()) {
                        throw new Exception("Error in saving local directorate decision details. Please check if all of its elements in the Excel file are filled in and valid (i.e. protocol, action, subject)");
                    }
                }

                foreach ($rowiterator as $row) {
                    $currentrow_index = $row->getRowIndex();

                    $currentteacher_am = trim($disposals_worksheet->getCellByColumnAndRow($disposals_columns['AM'], $currentrow_index)->getValue());
                    if ($currentteacher_am == "") {
                        break;
                    }

                    $teacher_model = Teacher::find()->where(['teacher_registrynumber' => $currentteacher_am])->orWhere(['teacher_afm' => $currentteacher_am])->one();

                    if (is_null($teacher_model)) {
                        throw new Exception(DisposalModule::t('modules/disposal/app', "There is no teacher in the database with Registry or VAT number " . $currentteacher_am  . ". Please add the teacher in the database to continue."));
                    }
                    /*if (!$teacher_model) {
                        $teacher_model = new Teacher();
                        $teacher_model->teacher_registrynumber = intval($currentteacher_am);
                        $teacher_model->teacher_surname = $disposals_worksheet->getCellByColumnAndRow($disposals_columns['SURNAME'], $currentrow_index)->getValue();
                        $teacher_model->teacher_name = $disposals_worksheet->getCellByColumnAndRow($disposals_columns['NAME'], $currentrow_index)->getValue();
                        $teacher_model->school_id = Schoolunit::findOne(['school_id' => self::findExcelFileSchoolId($disposals_worksheet->getCellByColumnAndRow($disposals_columns['SERVICE_SCHOOL'], $currentrow_index)->getValue())])['school_id'];

                        // Find the specialisation_id of the teacher
                        $specialisation = mb_substr($disposals_worksheet->getCellByColumnAndRow($disposals_columns['SPECIALISATION'], $currentrow_index)->getValue(), 0, 7, 'UTF-8');
                        $specialisation_with_blank = mb_substr($specialisation, 0, 2) . ' ' . mb_substr($specialisation, 2, 5, 'UTF-8');
                        if (mb_substr($specialisation, 4, 1, 'UTF-8') != '.') {
                            $specialisation = mb_substr($specialisation, 0, 4);
                            $specialisation_with_blank = mb_substr($specialisation_with_blank, 0, 5);
                        }
                        $specialisation_id = Specialisation::find()->where(['code' => $specialisation])->orWhere(['code' => $specialisation_with_blank])->one()['id'];
                        $teacher_model->specialisation_id = $specialisation_id;
                        if (!$teacher_model->save()) {
                            throw new Exception("Error in saving teacher details refered to the disposals. Please check if teacher details for all disposals in the Excel file are filled in and valid.");
                        }
                    } */

                    $disposal = new Disposal();
                    $startdate = null;
                    $enddate = null;
                    $startdate_cell = $disposals_worksheet->getCellByColumnAndRow($disposals_columns['START_DATE'], $currentrow_index);
                    $enddate_cell = $disposals_worksheet->getCellByColumnAndRow($disposals_columns['END_DATE'], $currentrow_index);

                    if (in_array($startdate_cell->getDataType(), [DataType::TYPE_STRING, DataType::TYPE_STRING2], true)) {
                        $startdate = $startdate_cell->getFormattedValue();
                        $startdate = str_replace("/", "-", $startdate);
                    } elseif ($startdate_cell->getDataType() == DataType::TYPE_NUMERIC) {
                        $startdate = $startdate_cell->getValue();
                        $startdate = Date::excelToDateTimeObject($startdate)->format("d-m-Y");
                    }

                    if (in_array($enddate_cell->getDataType(), [DataType::TYPE_STRING, DataType::TYPE_STRING2], true)) {
                        $enddate = $enddate_cell->getFormattedValue();
                        $enddate = str_replace("/", "-", $enddate);
                    } elseif ($enddate_cell->getDataType() == DataType::TYPE_NUMERIC) {
                        $enddate = $enddate_cell->getValue();
                        $enddate = Date::excelToDateTimeObject($enddate)->format("d-m-Y");
                        ;
                    }

                    $disposal->disposal_startdate = yii::$app->formatter->asDate($startdate, "php:Y-m-d");
                    $disposal->disposal_enddate = yii::$app->formatter->asDate($enddate, "php:Y-m-d");
                    if ($disposal->disposal_startdate > $disposal->disposal_enddate) {
                        throw new Exception("Some disposal(s) start date is later than its (their) end date.");
                    }
                    $disposal_hours = $disposals_worksheet->getCellByColumnAndRow($disposals_columns['HOURS'], $currentrow_index)->getValue();
                    $disposal_days = $disposals_worksheet->getCellByColumnAndRow($disposals_columns['DAYS'], $currentrow_index)->getValue();
                    if ($disposal_hours == "ΟΛΙΚΗ ΔΙΑΘΕΣΗ") {
                        $disposal_hours = Disposal::FULL_DISPOSAL;
                    }
                    if ($disposal_days == "ΟΛΙΚΗ ΔΙΑΘΕΣΗ") {
                        $disposal_days = Disposal::FULL_DISPOSAL;
                    } elseif (empty($disposal_days)) {
                        $disposal_days = 0;
                    }
                    $disposal->disposal_days = $disposal_days;
                    $disposal->disposal_hours = $disposal_hours;

                    $disposal->disposalreason_id = DisposalReason::findOne(['disposalreason_name' => self::getDisposalReasonUniqueName($disposals_worksheet->getCellByColumnAndRow($disposals_columns['DISPOSAL_REASON'], $currentrow_index)->getValue())])['disposalreason_id'];
                    $disposal->disposalworkobj_id = DisposalWorkobj::findOne(['disposalworkobj_name' => self::getDisposalDutyUniqueName($disposals_worksheet->getCellByColumnAndRow($disposals_columns['DISPOSAL_DUTY'], $currentrow_index)->getValue())])['disposalworkobj_id'];
                    $disposal->teacher_id = $teacher_model->teacher_id;
                    $disposal->fromschool_id = Schoolunit::findOne(['school_id' => self::findExcelFileSchoolId($disposals_worksheet->getCellByColumnAndRow($disposals_columns['SERVICE_SCHOOL'], $currentrow_index)->getValue())])['school_id'];
                    $disposal->toschool_id = Schoolunit::findOne(['school_id' => self::findExcelFileSchoolId($disposals_worksheet->getCellByColumnAndRow($disposals_columns['DISPOSAL_SCHOOL'], $currentrow_index)->getValue())])['school_id'];
                    $disposal->deleted = 0;
                    $disposal->archived = 0;
                    $disposal->localdirdecision_id = $localdir_dec->localdirdecision_id;

                    if (!$disposal->save()) {
                        throw new Exception("Error in saving dispoals details. Please check if the details for all disposals in the Excel file are filled in and valid.");
                    }
                }

                $transaction->commit();
                $user = Yii::$app->user->identity->username;
                Yii::info('User ' . $user . ' ' . 'imported disposals of ' .$directorate . ' from Excel file', 'disposal');
                Yii::$app->session->addFlash('success', DisposalModule::t('modules/disposal/app', "The disposals were imported successfully."));
                return $this->redirect(['index']);
            } else {
                return $this->render('importdisposals', [
                    'import_model' => $import_model,
                ]);
            }
        } catch (Exception $exc) {
            $transaction->rollBack();
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', $exc->getMessage()));
            return $this->redirect(['index']);
        } catch (\yii\base\InvalidParamException $invp_exc) {
            $transaction->rollBack();
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', $invp_exc->getMessage()));
            return $this->redirect(['index']);
        }
    }


    /**
     * Receives the school as it is in the Excel file for importing disposals and returns its id.
     * The schools in the Excel file has the form of "School_name (school_id)".
     *
     * @param string $school
     * #return string the school id
     */
    private static function findExcelFileSchoolId($school)
    {
        $school = rtrim($school, ")");
        $paranthesisOpening_pos = strrpos($school, "(");
        return (int)substr($school, $paranthesisOpening_pos+1);
    }


    /**
     * THIS IS A FUNCTION TIGHTLY COUPLED TO THE EXCEL FILES FILLED IN BY LOCAL DIRECOTORATES FOR IMPORTING DISPOSALS
     *
     * Returns the unique disposal reason name as it is stored in the database based on the $disposal_reason string given in the Excel files for the disposals
     *
     * @param string $disposal_reason
     * @throws Exception
     * @return string
     */
    private static function getDisposalReasonUniqueName($disposal_reason)
    {
        if ($disposal_reason == 'ΣΥΜΠΛΗΡΩΣΗ ΩΡΑΡΙΟΥ') {
            return 'supplementing_workinghours';
        } elseif ($disposal_reason == 'ΚΑΛΥΨΗ ΟΛΙΓΟΗΜΕΡΗΣ ΑΔΕΙΑΣ') {
            return 'cover_timeoff';
        } elseif ($disposal_reason == 'ΛΟΓΟΙ ΥΓΕΙΑΣ') {
            return 'health_reasons';
        } elseif ($disposal_reason == 'ΥΠΗΡΕΣΙΑΚΟΙ ΛΟΓΟΙ') {
            return 'service_reasons';
        } else {
            throw new Exception("Unknown disposal reason");
        }
    }


    /**
     * THIS IS A FUNCTION TIGHTLY COUPLED TO THE EXCEL FILES FILLED IN BY LOCAL DIRECOTORATES FOR IMPORTING DISPOSALS
     *
     * Returns the unique disposal duty name as it is stored in the database based on the $disposal_duty string given in the Excel files for the disposals
     *
     * @param string $disposal_duty
     * @throws Exception
     * @return string
     */
    private static function getDisposalDutyUniqueName($disposal_duty)
    {
        if ($disposal_duty == 'ΠΑΡΟΧΗ ΔΙΟΙΚΗΤΙΚΟΥ ΕΡΓΟΥ') {
            return 'administrative_work';
        } elseif ($disposal_duty == 'ΓΡΑΜΜΑΤΕΙΑΚΗ ΥΠΟΣΤΗΡΙΞΗ') {
            return 'secretary_work';
        } elseif ($disposal_duty == 'ΕΝΙΣΧΥΤΙΚΗ ΔΙΔΑΣΚΑΛΙΑ') {
            return 'supplementary_teaching';
        } elseif ($disposal_duty == '') {
            return 'not_defined';
        } else {
            throw new Exception("Unknown disposal duty");
        }
    }


    /**
     * THIS IS A FUNCTION TIGHTLY COUPLED TO THE EXCEL FILES FILLED IN BY LOCAL DIRECOTORATES FOR IMPORTING DISPOSALS
     *
     * Returns the id of the directorate as retrieved by mm.sch.gr and stored in the local database based on the directorate short name passed as argument.
     *
     * @param string $local_directorate
     * @throws Exception
     * @return integer
     */
    private static function getDirectorateId($local_directorate)
    {
        $local_directorate = strtoupper($local_directorate);

        if ($local_directorate == "ΔΔΕ ΗΡΑΚΛΕΙΟΥ") {
            return 15;
        } elseif ($local_directorate == "ΔΔΕ ΧΑΝΙΩΝ") {
            return 25;
        } elseif ($local_directorate == "ΔΔΕ ΡΕΘΥΜΝΟΥ") {
            return 100;
        } elseif ($local_directorate == "ΔΔΕ ΛΑΣΙΘΙΟΥ") {
            return 95;
        } elseif ($local_directorate == "ΔΠΕ ΗΡΑΚΛΕΙΟΥ") {
            return 41;
        } elseif ($local_directorate == "ΔΠΕ ΧΑΝΙΩΝ") {
            return 60;
        } elseif ($local_directorate == "ΔΠΕ ΡΕΘΥΜΝΟΥ") {
            return 75;
        } elseif ($local_directorate == "ΔΠΕ ΛΑΣΙΘΙΟΥ") {
            return 72;
        } else {
            throw new \Exception("Uknown directorate given.");
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
