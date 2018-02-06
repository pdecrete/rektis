<?php

namespace app\modules\finance\controllers;

use Yii;
use app\modules\finance\Module;
use app\modules\finance\models\FinanceState;
use app\modules\finance\models\FinanceStateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * FinanceStateController implements the CRUD actions for FinanceState model.
 */
class FinanceStateController extends Controller
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
                    ['actions' => ['index'], 'allow' => true, 'roles' => ['financial_viewer']],
                    ['allow' => true, 'roles' => ['financial_director']]
                ]]
        ];
    }

    /**
     * Lists all FinanceState models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FinanceStateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new FinanceState model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FinanceState();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The expenditure state was created successfully."));
            return $this->redirect(['index', 'id' => $model->state_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing FinanceState model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The expenditure state was created successfully."));
            return $this->redirect(['index', 'id' => $model->state_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing FinanceState model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(!$this->findModel($id)->delete()){
            Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failure in deleting the expenditure state."));
            return $this->redirect(['index', 'id' => $model->state_id]);
        }
        Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The expenditure state was deleted succesfully."));      
        return $this->redirect(['index']);
    }

    /**
     * Finds the FinanceState model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FinanceState the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FinanceState::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
