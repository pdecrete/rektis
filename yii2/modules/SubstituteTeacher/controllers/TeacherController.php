<?php

namespace app\modules\SubstituteTeacher\controllers;

use Yii;
use app\modules\SubstituteTeacher\models\Teacher;
use app\modules\SubstituteTeacher\models\TeacherSearch;
use app\modules\SubstituteTeacher\models\PlacementPreference;
use app\modules\SubstituteTeacher\models\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UnprocessableEntityHttpException;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

/**
 * TeacherController implements the CRUD actions for Teacher model.
 */
class TeacherController extends Controller
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
     * Lists all Teacher models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TeacherSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Teacher model.
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
     * Creates a new Teacher model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Teacher();
        $modelsPlacementPreferences = [new PlacementPreference];

        if ($model->load(Yii::$app->request->post())) {
            //  && $model->save()) {
            $modelsPlacementPreferences = Model::createMultiple(PlacementPreference::classname(), $modelsPlacementPreferences);
            Model::loadMultiple($modelsPlacementPreferences, Yii::$app->request->post());

            array_walk($modelsPlacementPreferences, function ($m) {
                $m->setScenario(PlacementPreference::SCENARIO_MASS_UPDATE);
            });

            // validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsPlacementPreferences) && $valid;

            $valid = PlacementPreference::checkOrdering($modelsPlacementPreferences) && $valid;
            $valid = PlacementPreference::checkRules($modelsPlacementPreferences) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) { // already validated
                        foreach ($modelsPlacementPreferences as $modelPlacementPreference) {
                            if (! ($flag = $modelPlacementPreference->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Ολοκληρώθηκε με επιτυχία η εισαγωγή των στοιχείων.');
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('danger', 'Δεν ολοκληρώθηκε η εισαγωγή των στοιχείων λόγω τεχνικού προβλήματος.');
                    Yii::$app->session->addFlash('danger', $e->getMessage());
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'modelsPlacementPreferences' => $modelsPlacementPreferences ? $modelsPlacementPreferences : [ new PlacementPreference]
        ]);
    }

    /**
     * Updates an existing Teacher model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelsPlacementPreferences = ($model->placementPreferences ? $model->placementPreferences : [new PlacementPreference]);

        if ($model->load(Yii::$app->request->post())) {
            $oldIDs = ArrayHelper::map($modelsPlacementPreferences, 'id', 'id');
            $modelsPlacementPreferences = Model::createMultiple(PlacementPreference::classname(), $modelsPlacementPreferences);
            Model::loadMultiple($modelsPlacementPreferences, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsPlacementPreferences, 'id', 'id')));

            array_walk($modelsPlacementPreferences, function ($m) use ($id) {
                $m->teacher_id = $id;
                $m->setScenario(PlacementPreference::SCENARIO_MASS_UPDATE);
            });

            // validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsPlacementPreferences) && $valid;

            $valid = PlacementPreference::checkOrdering($modelsPlacementPreferences) && $valid;
            $valid = PlacementPreference::checkRules($modelsPlacementPreferences) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) { // already validated
                        if (! empty($deletedIDs)) {
                            PlacementPreference::deleteAll(['id' => $deletedIDs]);
                        }
                        foreach ($modelsPlacementPreferences as $modelPlacementPreference) {
                            if (! ($flag = $modelPlacementPreference->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Ολοκληρώθηκε με επιτυχία η ενημέρωση των στοιχείων.');
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('danger', 'Δεν ολοκληρώθηκε η ενημέρωση των στοιχείων λόγω τεχνικού προβλήματος.');
                    Yii::$app->session->addFlash('danger', $e->getMessage());
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'modelsPlacementPreferences' => $modelsPlacementPreferences ? $modelsPlacementPreferences : [ new PlacementPreference]
        ]);
    }

    /**
     * Deletes an existing Teacher model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws UnprocessableEntityHttpException If the teacher is involved in any process
     */
    public function actionDelete($id)
    {
        // throw new UnprocessableEntityHttpException(Yii::t('substituteteacher', 'The teacher cannot be deleted.'));
        throw new \Exception('Not implemented yet');
    }

    /**
     * Finds the Teacher model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Teacher the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Teacher::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
