<?php

namespace app\modules\disposal\controllers;

use Exception;
use Yii;
use app\modules\disposal\DisposalModule;
use app\modules\disposal\models\DisposalLocaldirdecision;
use app\modules\disposal\models\DisposalLocaldirdecisionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\modules\schooltransport\models\Directorate;
use app\modules\disposal\models\Disposal;

/**
 * DisposalLocaldirdecisionController implements the CRUD actions for DisposalLocaldirdecision model.
 */
class DisposalLocaldirdecisionController extends Controller
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
                    ['actions' => ['index', 'view'], 'allow' => true, 'roles' => ['disposal_viewer']],
                    ['actions' => ['create', 'update', 'delete'], 'allow' => true, 'roles' => ['disposal_editor']],
                ]
            ]
        ];
    }

    /**
     * Lists all DisposalLocaldirdecision models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DisposalLocaldirdecisionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DisposalLocaldirdecision model.
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
     * Creates a new DisposalLocaldirdecision model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DisposalLocaldirdecision();
        $directorates = Directorate::find()->orderBy('directorate_shortname')->all();

        try {
            if ($model->load(Yii::$app->request->post())) {
                $model->localdirdecision_protocol = trim($model->localdirdecision_protocol);
                $model->localdirdecision_action = trim($model->localdirdecision_action);
                
                if (!$model->save()) {
                    throw new Exception("The creation of the decision decisions failed.");
                }
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'directorates' => $directorates
                ]);
            }

            $user = Yii::$app->user->identity->username;
            Yii::info('User ' . $user . ' ' . 'created Decision of Local Directorate with id: '. $model->localdirdecision_id, 'disposal');
            Yii::$app->session->addFlash('success', DisposalModule::t('modules/disposal/app', "The decision was created successfully."));
            return $this->redirect(['index']);
        } catch (Exception $exc) {
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', $exc->getMessage()));
            return $this->render('create', [
                'model' => $model,
                'directorates' => $directorates
            ]);
        }
    }

    /**
     * Updates an existing DisposalLocaldirdecision model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $directorates = Directorate::find()->orderBy('directorate_shortname')->all();

        try {
            if ($model->load(Yii::$app->request->post())) {
                $assigned_disposals = Disposal::findAll(['localdirdecision_id' => $model->localdirdecision_id]);
                if (count($assigned_disposals) >= 1 && $assigned_disposals[0]->getTeacherDirectorate()->directorate_id != $model->directorate_id) {
                    throw new Exception("The decision cannot change because the already assigned disposals to the decision refer to schools of teachers of different Directorate.");
                }
                $model->localdirdecision_protocol = trim($model->localdirdecision_protocol);
                $model->localdirdecision_action = trim($model->localdirdecision_action);
                if (!$model->save()) {
                    throw new Exception("The update of the decision of the directorate failed.");
                }
            } else {
                return $this->render('update', [
                    'model' => $model,
                    'directorates' => $directorates
                ]);
            }

            $user = Yii::$app->user->identity->username;
            Yii::info('User ' . $user . ' ' . 'updated Decision of Local Directorate with id: '. $model->localdirdecision_id, 'disposal');
            Yii::$app->session->addFlash('success', DisposalModule::t('modules/disposal/app', "The decision of the directorate was updated successfully."));
            return $this->redirect(['index']);
        } catch (Exception $exc) {
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', $exc->getMessage()));
            return $this->render('update', [
                'model' => $model,
                'directorates' => $directorates
            ]);
        }
    }

    /**
     * Deletes an existing DisposalLocaldirdecision model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            if (!$this->findModel($id)->delete()) {
                throw new Exception("The decision of the directorate cannot be deleted.");
            }
            $user = Yii::$app->user->identity->username;
            Yii::info('User ' . $user . ' ' . 'created Decision of Local Directorate with id: '. $id, 'disposal');
            Yii::$app->session->addFlash('success', DisposalModule::t('modules/disposal/app', "The decision of the directorate was deleted successfully."));
            return $this->redirect(['index']);
        } catch (Exception $exc) {
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', $exc->getMessage()));
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the DisposalLocaldirdecision model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DisposalLocaldirdecision the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DisposalLocaldirdecision::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
