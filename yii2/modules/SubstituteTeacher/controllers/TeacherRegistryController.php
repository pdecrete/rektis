<?php

namespace app\modules\SubstituteTeacher\controllers;

use Yii;
use app\modules\SubstituteTeacher\models\TeacherRegistry;
use app\modules\SubstituteTeacher\models\TeacherRegistrySearch;
use app\models\Specialisation;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UnprocessableEntityHttpException;

/**
 * TeacherRegistryController implements the CRUD actions for TeacherRegistry model.
 */
class TeacherRegistryController extends Controller
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
     * Lists all TeacherRegistry models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TeacherRegistrySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TeacherRegistry model.
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
     * Creates a new TeacherRegistry model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TeacherRegistry();

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->save()) {
                    if (!empty($model->specialisation_ids)) {
                        $specialisations = Specialisation::findAll(['id' => $model->specialisation_ids]);
                        foreach ($specialisations as $specialisation) {
                            $model->link('specialisations', $specialisation);
                        }
                    }
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    Yii::$app->session->setFlash('danger', 'Δεν ολοκληρώθηκε η εισαγωγή των στοιχείων.');
                    $transaction->rollBack();
                }
            } catch (Exception $e) {
                Yii::$app->session->setFlash('danger', 'Δεν ολοκληρώθηκε η εισαγωγή των στοιχείων λόγω τεχνικού προβλήματος.');
                $transaction->rollBack();
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TeacherRegistry model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->save()) {
                    $model->unlinkAll('specialisations');
                    if (!empty($model->specialisation_ids)) {
                        $specialisations = Specialisation::findAll(['id' => $model->specialisation_ids]);
                        foreach ($specialisations as $specialisation) {
                            $model->link('specialisations', $specialisation);
                        }
                    }
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    Yii::$app->session->setFlash('danger', 'Δεν ολοκληρώθηκε η ενημέρωση των στοιχείων.');
                    $transaction->rollBack();
                }
            } catch (Exception $e) {
                Yii::$app->session->setFlash('danger', 'Δεν ολοκληρώθηκε η ενημέρωση των στοιχείων λόγω τεχνικού προβλήματος.');
                $transaction->rollBack();
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TeacherRegistry model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws UnprocessableEntityHttpException If the teacher is involved in any process
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->teachers) {
            throw new UnprocessableEntityHttpException(Yii::t('substituteteacher', 'The teacher cannot be deleted from the registry because the teacher has been selected at sometime.'));
        } else {
            $model->delete();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the TeacherRegistry model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TeacherRegistry the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TeacherRegistry::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
