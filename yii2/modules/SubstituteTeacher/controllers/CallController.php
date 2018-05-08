<?php
namespace app\modules\SubstituteTeacher\controllers;

use Yii;
use app\modules\SubstituteTeacher\models\Call;
use app\modules\SubstituteTeacher\models\CallSearch;
use app\modules\SubstituteTeacher\models\CallTeacherSpecialisation;
use app\modules\SubstituteTeacher\models\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * CallController implements the CRUD actions for Call model.
 */
class CallController extends Controller
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
     * Lists all Call models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CallSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Call model.
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
     * Creates a new Call model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Call();
        $modelsCallTeacherSpecialisation = [new CallTeacherSpecialisation];

        if ($model->load(Yii::$app->request->post())) {
            $modelsCallTeacherSpecialisation = Model::createMultiple(CallTeacherSpecialisation::classname(), $modelsCallTeacherSpecialisation);
            Model::loadMultiple($modelsCallTeacherSpecialisation, Yii::$app->request->post());

            // validate model now, dependencies after we get an id
            $valid = $model->validate();

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) { // already validated
                        $model->refresh();
                        $id = $model->id;
                        array_walk($modelsCallTeacherSpecialisation, function ($m) use ($id) {
                            $m->call_id = $id;
                        });
                        // validate dependent models 
                        $flag = Model::validateMultiple($modelsCallTeacherSpecialisation);
                        if ($flag) {
                            foreach ($modelsCallTeacherSpecialisation as $modelCTS) {
                                if (! ($flag = $modelCTS->save(false))) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        } else {
                            Yii::$app->session->setFlash('danger', 'Μη έγκυρα στοιχεία καθηγητών.');                            
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
            'modelsCallTeacherSpecialisation' => $modelsCallTeacherSpecialisation ? $modelsCallTeacherSpecialisation : [ new CallTeacherSpecialisation]
        ]);
    }

    /**
     * Updates an existing Call model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelsCallTeacherSpecialisation = ($model->callTeacherSpecialisations ? $model->callTeacherSpecialisations : [new CallTeacherSpecialisation]);

        if ($model->load(Yii::$app->request->post())) {
            $oldIDs = ArrayHelper::map($modelsCallTeacherSpecialisation, 'id', 'id');
            $modelsCallTeacherSpecialisation = Model::createMultiple(CallTeacherSpecialisation::classname(), $modelsCallTeacherSpecialisation);
            Model::loadMultiple($modelsCallTeacherSpecialisation, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsCallTeacherSpecialisation, 'id', 'id')));

            array_walk($modelsCallTeacherSpecialisation, function ($m) use ($id) {
                $m->call_id = $id;
            });

            // validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsCallTeacherSpecialisation) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) { // already validated
                        if (! empty($deletedIDs)) {
                            CallTeacherSpecialisation::deleteAll(['id' => $deletedIDs]);
                        }
                        foreach ($modelsCallTeacherSpecialisation as $modelCTS) {
                            if (! ($flag = $modelCTS->save(false))) {
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
        return $this->render('update', compact('model', 'modelsCallTeacherSpecialisation'));
    }

    /**
     * Deletes an existing Call model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Call model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Call the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Call::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
