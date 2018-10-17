<?php

namespace app\modules\SubstituteTeacher\controllers;

use Yii;
use app\modules\SubstituteTeacher\models\Model;
use app\modules\SubstituteTeacher\models\Placement;
use app\modules\SubstituteTeacher\models\PlacementSearch;
use app\modules\SubstituteTeacher\models\PlacementPosition;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\modules\SubstituteTeacher\models\Teacher;
use app\modules\SubstituteTeacher\models\Application;
use yii\db\Expression;
use app\modules\SubstituteTeacher\models\CallPosition;
use app\modules\SubstituteTeacher\models\PlacementTeacher;
use app\modules\SubstituteTeacher\models\PlacementPrint;

/**
 * PlacementController implements the CRUD actions for Placement model.
 */
class PlacementController extends Controller
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
                    'place' => ['POST'],
                    'print' => ['POST'],
                    'placement-print' => ['POST'],
                    'download-decision' => ['POST'],
                    'download-summary' => ['POST'],
                    'download-contract' => ['POST'],
                    'download-all' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'place', 'print'],
                        'allow' => true,
                        'roles' => ['admin', 'spedu_user'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Placement models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PlacementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Placement model.
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
     * Place a teacher based on his application information.
     * Redirects back to the application.
     * @param int $application_id the application id
     * @param mixed payload(post) contains various parameters:
     *      - int call_position_id MANDATORY
     *      - int placement_id MANDATORY
     * @return mixed
     */
    public function actionPlace($application_id)
    {
        // locate application and placement
        $placement_id = Yii::$app->request->post('placement_id', -1);
        $application = Application::findOne($application_id);
        $placement = Placement::findOne($placement_id);
        if (empty($application)) {
            Yii::$app->session->setFlash('danger', Yii::t('substituteteacher', 'Application not found.'));
        } elseif (empty($placement)) {
            Yii::$app->session->setFlash('danger', Yii::t('substituteteacher', 'Placement not found.' . var_export($placement_id, true)));
        } else {
            $call_position_id = Yii::$app->request->post('call_position_id', -1); // fails if not passed correctly
            $teacher_board = $application->teacherBoard;
            $call_position_ids = array_map(function ($m) {
                return $m->call_position_id;
            }, $application->applicationPositions);
            $call_positions = CallPosition::findAllInGroupOfCallPosition($call_position_id);
            if (!in_array($call_position_id, $call_position_ids, false) || empty($call_positions)) {
                Yii::$app->session->setFlash('danger', Yii::t('substituteteacher', 'Call position not found.'));
            } else {
                $transaction = \Yii::$app->db->beginTransaction();

                $model = new PlacementTeacher();

                // get information for placement
                $model->teacher_board_id = $teacher_board->id;
                $model->placement_id = $placement_id;
                $model->dismissed = false;
                $model->altered = false;
                $model->cancelled = false;
    
                if (!$model->save()) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('danger', Yii::t('substituteteacher', 'There was an error creating the teacher placement.'));
                    Yii::$app->session->addFlash('danger', array_reduce(array_values($model->getErrors()), function ($c, $v) {
                        return $c . implode(' ', $v) . ' ';
                    }, ''));
                } else {
                    $model->refresh();
                    $saved_positions = true;
                    $saved_positions_info = [];
                    foreach ($call_positions as $call_position) {
                        $placement_position_model = new PlacementPosition;
                        $placement_position_model->placement_teacher_id = $model->id;
                        $placement_position_model->position_id = $call_position->position_id;
                        $placement_position_model->teachers_count = $call_position->teachers_count;
                        $placement_position_model->hours_count = $call_position->hours_count;
                        if (!$placement_position_model->save()) {
                            $transaction->rollBack();
                            $saved_positions = false;
                            Yii::$app->session->setFlash('danger', Yii::t('substituteteacher', 'There was an error saving the placement position.'));
                            Yii::$app->session->addFlash('danger', array_reduce(array_values($placement_position_model->getErrors()), function ($c, $v) {
                                return $c . implode(' ', $v) . ' ';
                            }, ''));
                            break;
                        } else {
                            $placement_position_model->refresh();
                            $saved_positions_info[] = $placement_position_model->getAttributes();
                        }
                    }

                    if ($saved_positions) {
                        // mark teacher as appointed
                        $teacher_board->status = Teacher::TEACHER_STATUS_APPOINTED;
                        if (!$teacher_board->save()) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('danger', Yii::t('substituteteacher', 'Teacher status could not be updated.'));
                        } else {
                            $transaction->commit();
                            Yii::$app->session->setFlash('success', Yii::t('substituteteacher', 'Placement completed successfully.'));
                            $teacher_board->teacher->audit('Τοποθέτηση αναπληρωτή από αίτηση', [
                                'application' => $application_id,
                                'placement' => $placement_id,
                                'call_position_id' => $call_position_id,
                                'call_position_ids' => $call_position_ids,
                                'PlacementTeacher' => $model->getAttributes(),
                                'PlacementPosition' => $saved_positions_info
                            ]);
                        }
                    }
                }
            }
        }

        return $this->redirect(['application/view', 'id' => $application_id]);
    }

    /**
     * Creates a new Placement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Placement();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Placement model. The teacher board should not be altered.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Placement model.
     * Also marks the teacher board as TEACHER_STATUS_ELIGIBLE.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $transaction = \Yii::$app->db->beginTransaction();

        $model = $this->findModel($id);
        $ok = true;

        // first, delete any dependent teachers placements
        $placement_teachers = $model->placementTeachers;
        if (!empty($placement_teachers)) {
            foreach ($placement_teachers as $placement_teacher_model) {
                $teacher_board = $placement_teacher_model->teacherBoard;
                if ($placement_teacher_model->delete() === false) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('danger', Yii::t('substituteteacher', 'Teacher placement could not be marked as deleted.'));
                    $ok = false;
                } else {
                    // mark teacher as eligible again
                    $teacher_board->status = Teacher::TEACHER_STATUS_ELIGIBLE;
                    if (!$teacher_board->save(false, ['status'])) {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('danger', Yii::t('substituteteacher', 'Teacher status could not be updated.'));
                        $ok = false;
                    }
                }
            }
        }

        if ($ok === true) {
            $model->deleted = true;
            $model->deleted_at = new Expression('CURRENT_TIMESTAMP()');

            if (!$model->save(false, ['deleted', 'deleted_at'])) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('danger', Yii::t('substituteteacher', 'Placement could not be marked as deleted.'));
            } else {
                $transaction->commit();
                Yii::$app->session->setFlash('success', Yii::t('substituteteacher', 'Placement has been marked as deleted.'));
            }
        } 

        return $this->redirect(['index']);
    }

    protected function downloadDocument($id, $ptid, $type)
    {
        // provided a specific placement teacher (ptid), use that one only!
        if (!empty($ptid)) {
            $model = PlacementTeacher::findOne($ptid);
            if (!empty($model) && ($model->cancelled || $model->dismissed || $model->altered)) {
                throw new NotFoundHttpException(Yii::t('substituteteacher', 'The requested placement is either cancelled, dismissed or altered.'));
            }
            $redirect_placement_id = $model->placement_id;
        } else {
            $model = Placement::findOne($id);
            $redirect_placement_id = $id;
        }
        if (empty($model)) {
            throw new NotFoundHttpException(Yii::t('substituteteacher', 'The requested placement does not exist.'));
        }

        if (($prints = $model->prints) != null) {
            $applicable_prints = array_filter($prints, function ($m) use ($type) {
                return $m->type === $type;
            });
            if (count($applicable_prints) == 1) {
                $print = reset($applicable_prints);
                $download_filename = PlacementPrint::getFilenameAbspath($print->filename, 'export');
                if (is_file($download_filename) && is_readable($download_filename)) {
                    Yii::$app->response->sendFile($download_filename);
                    Yii::$app->end();
                } else {
                    Yii::$app->session->setFlash('danger', Yii::t('substituteteacher', 'The placement document does not exist or it is not readable.'));
                }
            } else {
                Yii::$app->session->setFlash('danger', Yii::t('substituteteacher', 'There are {d} printed placement documents.', ['d' => count($applicable_prints)]));
            }
        } else {
            Yii::$app->session->setFlash('danger', Yii::t('substituteteacher', 'No placement documents have been printed.'));
        }
        return $this->redirect(['view', 'id' => $redirect_placement_id]);
    }

    public function actionDownloadDecision($id) 
    {
        return $this->downloadDocument($id, null, 'decision');
    }

    /**
     * The id here is for the placementTeacher model
     */
    public function actionDownloadSummary($id)
    {
        return $this->downloadDocument(null, $id, 'summary');
    }

    /**
     * The id here is for the placementTeacher model
     */
    public function actionDownloadContract($id)
    {
        return $this->downloadDocument(null, $id, 'contract');
    }

    public function actionDownloadAll($id) 
    {
        $sanitize_filename_pattern = '/[^\wΑ-Ζα-ζ\s\d\.\-_,]/';

        $placement = $this->findModel($id);

        try {
            $prints = $placement->prints;
            if (empty($prints)) {
                throw new \Exception(Yii::t('substituteteacher', 'No documents were located.'));
            } else {
                $tempfilename = tempnam(sys_get_temp_dir(), 'doc');

                $zip = new \ZipArchive();
                if ($zip->open($tempfilename, \ZipArchive::CREATE) !== true) {
                    throw new \Exception(Yii::t('substituteteacher', 'Cannot create file for download.'));
                }
                foreach ($placement->prints as $idx => $print) {
                    $print_filename = PlacementPrint::getFilenameAbspath($print->filename, 'export');
                    if (is_file($print_filename) && is_readable($print_filename)) {
                        $store_filename = pathinfo($print_filename, PATHINFO_FILENAME);
                        if ($print->type === 'decision') {
                            $count = -1;
                            $store_filename = preg_replace($sanitize_filename_pattern, "-", $placement->label, -1, $count);
                        } else {
                            if (!empty($print->placementTeacher)) {
                                $store_filename = preg_replace($sanitize_filename_pattern, "-", $print->placementTeacher->teacherBoard->teacher->registry->name);
                            }
                        }
                        $store_filename = $print->type . '_' . $store_filename . '.' . pathinfo($print_filename, PATHINFO_EXTENSION);
                        $zip->addFile($print_filename, $store_filename);
                    } else {
                        throw new \Exception(Yii::t('substituteteacher', 'A placement document does not exist or it is not readable.'));
                    }
                }
                $zip->close();

                $zip_send_filename = preg_replace($sanitize_filename_pattern, "-", "{$placement->label}.zip");
                if (empty($zip_send_filename)) {
                    $zip_send_filename = 'docs.zip';
                }
                Yii::$app->response->sendFile($tempfilename, $zip_send_filename, ['mimeType' => 'application/zip']);
                Yii::$app->end();
            }
        } catch (\Exception $ex) {
            Yii::$app->session->setFlash('danger', $ex->getMessage());
            return $this->redirect(['view', 'id' => $id]);
        }
    }

    public function actionPrintDecision($id)
    {
        // get a list of ids to help navigate necessary models
        $placement = $this->findModel($id);

        $print = new PlacementPrint();
        $print->placement_id = $placement->id;
        $print->placement_teacher_id = null;
        $print->type = PlacementPrint::TYPE_DECISION;
        $print->deleted = PlacementPrint::PRINT_NOT_DELETED;
        $print->generatePrint($placement, null, null);
        $print->save();

        // TODO remove previously generated decision documents or not?
        Yii::$app->session->setFlash('success', Yii::t('substituteteacher', 'Placement decision document generated successfully.'));
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Given a placement, locate all placements and generate placement documents.
     *
     * @throws NotFoundHttpException if placement cannot be found
     */
    public function actionPrint($id)
    {
        // get a list of ids to help navigate necessary models
        $placement_related_ids = Placement::getRelatedIds($id);

        $placement = $this->findModel($id);

        // get a list of teacher placements
        $placement_teachers = $placement->activePlacementTeachers;

        // TODO wrap all in a transaction and perfmorm error control

        // some prints have to be generated per teacher
        foreach ($placement_teachers as $placement_teacher) {
            // mark previous data as deleted; just do this once
            $deletions = PlacementPrint::updateAll([
                'deleted' => PlacementPrint::PRINT_DELETED,
                'deleted_at' => new Expression('NOW()'),
                'updated_at' => new Expression('NOW()')
            ], [
                'deleted' => PlacementPrint::PRINT_NOT_DELETED,
                'placement_id' => $placement->id,
                'placement_teacher_id' => $placement_teacher->id
            ]);

            $summary_print = new PlacementPrint();
            $summary_print->placement_id = $placement->id;
            $summary_print->placement_teacher_id = $placement_teacher->id;
            $summary_print->type = PlacementPrint::TYPE_SUMMARY;
            $summary_print->deleted = PlacementPrint::PRINT_NOT_DELETED;
            $summary_print->generatePrint($placement, $placement_teacher, $placement_related_ids);
            $summary_print->save(); // TODO add error control

            $contract_print = new PlacementPrint();
            $contract_print->placement_id = $placement->id;
            $contract_print->placement_teacher_id = $placement_teacher->id;
            $contract_print->type = PlacementPrint::TYPE_CONTRACT;
            $contract_print->deleted = PlacementPrint::PRINT_NOT_DELETED;
            $contract_print->generatePrint($placement, $placement_teacher, $placement_related_ids);
            $contract_print->save(); // TODO add error control
        }
        Yii::$app->session->setFlash('success', Yii::t('substituteteacher', 'Summary and contract documents generated successfully.'));

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Finds the Placement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Placement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Placement::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('substituteteacher', 'The requested placement does not exist.'));
        }
    }
}
