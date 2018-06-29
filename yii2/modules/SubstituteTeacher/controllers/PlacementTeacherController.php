<?php

namespace app\modules\SubstituteTeacher\controllers;

use Yii;
use app\modules\SubstituteTeacher\models\Model;
use app\modules\SubstituteTeacher\models\PlacementTeacher;
use app\modules\SubstituteTeacher\models\PlacementTeacherSearch;
use app\modules\SubstituteTeacher\models\PlacementPosition;
use app\modules\SubstituteTeacher\models\Placement;
use app\modules\SubstituteTeacher\models\Teacher;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\db\Expression;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

/**
 * PlacementTeacherController implements the CRUD actions for PlacementTeacher model.
 */
class PlacementTeacherController extends Controller
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
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update'],
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
     * Lists all PlacementTeacher models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PlacementTeacherSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PlacementTeacher model.
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
     * Mark an existing PlacementTeacher model as altered.
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

        return $this->redirect(['placement/view', 'id' => $model->placement_id]);
    }

    /**
     * Deletes an existing PlacementTeacher model.
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

        return $this->redirect(['placement/view', 'id' => $model->placement_id]);
    }

    /**
     * Creates a new PlacementTeacher model.
     * If creation is successful, the browser will be redirected to the
     * corresponding placement's view page.
     * @param null|int $placement_id the placement to place the teacher to
     * @return mixed
     */
    public function actionCreate($placement_id = null)
    {
        $model = new PlacementTeacher();
        $model->placement_id = $placement_id;
        $modelsPlacementPositions = [new PlacementPosition];

        if ($model->load(Yii::$app->request->post())) {
            // this enables the placement_id to change too but that's ok
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
                                $m->placement_teacher_id = $id;
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
                        Yii::$app->session->setFlash('success', Yii::t('substituteteacher', 'Teacher placement created successfully.'));
                        return $this->redirect(['placement/view', 'id' => $model->placement_id]);
                    } else {
                        Yii::$app->session->setFlash('danger', Html::errorSummary($modelsPlacementPositions));
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('danger', Yii::t('substituteteacher', 'There was an error creating the teacher placement.'));
                    Yii::$app->session->addFlash('danger', $e->getMessage());
                }
            } else {
                Yii::$app->session->setFlash('danger', Html::errorSummary($model));
            }
        }

        return $this->render('create', [
            'model' => $model,
            'modelsPlacementPositions' => $modelsPlacementPositions ? $modelsPlacementPositions : [ new PlacementPosition],
            'placement_id' => $placement_id
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
        $model->setScenario(PlacementTeacher::SCENARIO_UPDATE);
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
                $m->placement_teacher_id = $id;
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
                        return $this->redirect(['placement/view', 'id' => $model->placement_id]);
                    } else {
                        Yii::$app->session->setFlash('danger', Html::errorSummary($modelsPlacementPositions));
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('danger', Yii::t('substituteteacher', 'There was an error updating the placement.'));
                    Yii::$app->session->addFlash('danger', $e->getMessage());
                }
            } else {
                Yii::$app->session->setFlash('danger', Html::errorSummary($model) . Html::errorSummary($modelsPlacementPositions));
            }
        }

        return $this->render('update', [
            'model' => $model,
            'modelsPlacementPositions' => $modelsPlacementPositions ? $modelsPlacementPositions : [ new PlacementPosition]
        ]);
    }

    /**
     * Finds the PlacementTeacher model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PlacementTeacher the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PlacementTeacher::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
