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
use yii\helpers\ArrayHelper;

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
                    'alter' => ['POST'],
                    'place' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'place'],
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
     * @param int $call_position_id the call position to place to; this may be part of a group of positions wi which case placement is done accordingly
     * @return mixed
     */
    public function actionPlace($application_id, $call_position_id)
    {
        // locate application
        $application = Application::findOne($application_id);
        if (empty($application)) {
            Yii::$app->session->setFlash('danger', Yii::t('substituteteacher', 'Application not found.'));
        } else {
            $teacher_board = $application->teacherBoard;
            $call_position_ids = array_map(function ($m) {
                return $m->call_position_id;
            }, $application->applicationPositions);
            $call_positions = CallPosition::findAllInGroupOfCallPosition($call_position_id);
            if (!in_array($call_position_id, $call_position_ids, false) || empty($call_positions)) {
                Yii::$app->session->setFlash('danger', Yii::t('substituteteacher', 'Call position not found.'));
            } else {
                $transaction = \Yii::$app->db->beginTransaction();

                $model = new Placement();
                $model->teacher_board_id = $teacher_board->id;
                $model->call_id = $application->call_id;
                $model->date = new Expression('CURRENT_DATE()');
                $model->decision_board = '';
                $model->decision = '';
                $model->comments = "ONE-CLICK PLACEMENT FROM APPLICATION {$application_id}, POSITION {$call_position_id}";
                $model->deleted = false;
                if (!$model->save()) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('danger', Yii::t('substituteteacher', 'There was an error creating the placement.'));
                    Yii::$app->session->addFlash('danger', array_reduce(array_values($model->getErrors()), function ($c, $v) {
                        return $c . implode(' ', $v) . ' ';
                    }, ''));
                } else {
                    $model->refresh();
                    $saved_positions = true;
                    foreach ($call_positions as $call_position) {
                        $placement_position_model = new PlacementPosition;
                        $placement_position_model->placement_id = $model->id;
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
        $modelsPlacementPositions = [new PlacementPosition];

        if ($model->load(Yii::$app->request->post())) {
            $model->deleted = false;
            $model->altered = false;

            $post = \Yii::$app->request->post();

            if (isset($post['PlacementPosition'])) {
                $post['PlacementPosition'] = array_values($post['PlacementPosition']);
            }
            $modelsPlacementPositions = Model::createMultiple(PlacementPosition::classname(), $modelsPlacementPositions);
            Model::loadMultiple($modelsPlacementPositions, Yii::$app->request->post());

            $valid = $model->validate() && count($modelsPlacementPositions) > 0;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) { // already validated
                        $model->refresh();

                        // mark teacher as appointed
                        $teacher_board = $model->teacherBoard;
                        $teacher_board->status = Teacher::TEACHER_STATUS_APPOINTED;
                        if ($flag = $teacher_board->save()) {
                            // save placement positions
                            $id = $model->id;
                            array_walk($modelsPlacementPositions, function ($m) use ($id) {
                                $m->placement_id = $id;
                            });

                            if ($flag = Model::validateMultiple($modelsPlacementPositions)) {
                                foreach ($modelsPlacementPositions as $modelPlacementPosition) {
                                    if (! ($flag = $modelPlacementPosition->save())) {
                                        $transaction->rollBack();
                                        break;
                                    }
                                }
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', Yii::t('substituteteacher', 'Placement created successfully.'));
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('danger', Yii::t('substituteteacher', 'There was an error creating the placement.'));
                    Yii::$app->session->addFlash('danger', $e->getMessage());
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'modelsPlacementPositions' => $modelsPlacementPositions ? $modelsPlacementPositions : [ new PlacementPosition]
        ]);
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
        $model->setScenario(Placement::SCENARIO_UPDATE);
        $modelsPlacementPositions = ($model->placementPositions ? $model->placementPositions : [new PlacementPosition]);

        if ($model->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();

            if (isset($post['PlacementPosition'])) {
                $post['PlacementPosition'] = array_values($post['PlacementPosition']);
            }
            $oldIDs = ArrayHelper::map($modelsPlacementPositions, 'id', 'id');
            $modelsPlacementPositions = Model::createMultiple(PlacementPosition::classname(), $modelsPlacementPositions);
            Model::loadMultiple($modelsPlacementPositions, $post);
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsPlacementPositions, 'id', 'id')));

            array_walk($modelsPlacementPositions, function ($m) use ($id) {
                $m->placement_id = $id;
            });

            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsPlacementPositions) && $valid;

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) { // validated beforehand
                        if (! empty($deletedIDs)) {
                            PlacementPosition::deleteAll(['id' => $deletedIDs]);
                        }
                        foreach ($modelsPlacementPositions as $modelPlacementPosition) {
                            if (! ($flag = $modelPlacementPosition->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', Yii::t('substituteteacher', 'Placement updated successfully.'));
                        return $this->redirect(['view', 'id' => $model->id]);
                    } else {
                        Yii::$app->session->setFlash('info', "PAP!");
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('danger', Yii::t('substituteteacher', 'There was an error updating the placement.'));
                    Yii::$app->session->addFlash('danger', $e->getMessage());
                }
            } else {
                Yii::$app->session->setFlash('info', "PAP PAP!" . print_r($model->getErrors(), true));
            }
        }

        return $this->render('update', [
            'model' => $model,
            'modelsPlacementPositions' => $modelsPlacementPositions ? $modelsPlacementPositions : [ new PlacementPosition]
        ]);
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
        $teacher_board = $model->teacherBoard;
        $model->deleted = true;
        $model->deleted_at = new  Expression('CURRENT_TIMESTAMP()');

        if (!$model->save(false, ['deleted', 'deleted_at'])) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('danger', Yii::t('substituteteacher', 'Placement could not be marked as deleted.'));
        } else {
            // mark teacher as eligible again
            $teacher_board->status = Teacher::TEACHER_STATUS_ELIGIBLE;
            if (!$teacher_board->save(false, ['status'])) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('danger', Yii::t('substituteteacher', 'Teacher status could not be updated.'));
            } else {
                $transaction->commit();
                Yii::$app->session->setFlash('success', Yii::t('substituteteacher', 'Placement has been marked as deleted.'));
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * Mark an existing Placement model as altered.
     * Also marks the teacher board as TEACHER_STATUS_PENDING.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionAlter($id)
    {
        $transaction = \Yii::$app->db->beginTransaction();

        $model = $this->findModel($id);
        $teacher_board = $model->teacherBoard;
        $model->altered = true;
        $model->altered_at = new  Expression('CURRENT_TIMESTAMP()');

        if (!$model->save(false, ['altered', 'altered_at'])) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('danger', Yii::t('substituteteacher', 'Placement could not be marked as altered.'));
        } else {
            // mark teacher as pending
            $teacher_board->status = Teacher::TEACHER_STATUS_PENDING;
            if (!$teacher_board->save(false, ['status'])) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('danger', Yii::t('substituteteacher', 'Teacher status could not be updated.'));
            } else {
                $transaction->commit();
                Yii::$app->session->setFlash('success', Yii::t('substituteteacher', 'Placement has been marked as altered.'));
            }
        }

        return $this->redirect(['index']);
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
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
