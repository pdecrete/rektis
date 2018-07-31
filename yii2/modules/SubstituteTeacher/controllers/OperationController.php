<?php
namespace app\modules\SubstituteTeacher\controllers;

use Yii;
use app\modules\SubstituteTeacher\models\Operation;
use app\modules\SubstituteTeacher\models\OperationSearch;
use app\modules\SubstituteTeacher\models\Specialisation;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UnprocessableEntityHttpException;

/**
 * OperationController implements the CRUD actions for Operation model.
 */
class OperationController extends Controller
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
     * Lists all Operation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OperationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Operation model.
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
     * Creates a new Operation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Operation();

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->save()) {
                    // $model->unlinkAll('specialisations');
                    if (!empty($model->specialisation_ids)) {
                        $specialisations = Specialisation::findAll(['id' => $model->specialisation_ids]);
                        foreach ($specialisations as $specialisation) {
                            $model->link('specialisations', $specialisation);
                        }
                    }
                    Yii::$app->session->setFlash('success', 'Ολοκληρώθηκε με επιτυχία η εισαγωγή των στοιχείων της πράξης.');
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    Yii::$app->session->setFlash('danger', 'Δεν ολοκληρώθηκε η εισαγωγή της πράξης.');
                    $transaction->rollBack();
                }
            } catch (Exception $e) {
                Yii::$app->session->setFlash('danger', 'Δεν ολοκληρώθηκε η εισαγωγή της πράξης λόγω τεχνικού προβλήματος.');
                $transaction->rollBack();
            }
        }

        return $this->render('create', [
                'model' => $model,
        ]);
    }

    /**
     * Updates an existing Operation model.
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
                    Yii::$app->session->setFlash('success', 'Ολοκληρώθηκε με επιτυχία η ενημέρωση των στοιχείων της πράξης.');
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    Yii::$app->session->setFlash('danger', 'Δεν ολοκληρώθηκε η ενημέρωση της πράξης.');
                    $transaction->rollBack();
                }
            } catch (Exception $e) {
                Yii::$app->session->setFlash('danger', 'Δεν ολοκληρώθηκε η ενημέρωση της πράξης λόγω τεχνικού προβλήματος.');
                $transaction->rollBack();
            }
        }

        return $this->render('update', [
                'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Operation model.
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
     *
     * @param integer $id The id of the operation 
     * @param string $type Which template do download ('contract', 'summary')
     * @throws yii\web\UnprocessableEntityHttpException 
     * @throws yii\web\NotFoundHttpException 
     */
    public function actionDownloadTemplate($id, $type)
    {
        $model = $this->findModel($id);
        switch ($type) {
            case 'contract':
                $attribute = 'contract_template';
                break;
            case 'summary':
                $attribute = 'summary_template';
                break;
            case 'decision':
                $attribute = 'decision_template';
                break;
            default:
                throw new UnprocessableEntityHttpException(Yii::t('substituteteacher', 'The template type parameter is not recognised.'));
                break;
        }

        if (empty($model->$attribute)) {
            throw new NotFoundHttpException(Yii::t('substituteteacher', 'The template is not set.'));
        }

        $filename = Yii::getAlias("@vendor/admapp/resources/operations/{$model->$attribute}");
        if (!is_readable($filename)) {
            throw new NotFoundHttpException(Yii::t('substituteteacher', 'The template does not exist.'));
        }

        Yii::$app->response->sendFile($filename);
        Yii::$app->end();
    }

    /**
     * Finds the Operation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Operation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Operation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
