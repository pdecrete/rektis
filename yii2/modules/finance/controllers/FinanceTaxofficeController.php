<?php

namespace app\modules\finance\controllers;

use Yii;
use app\modules\finance\Module;
use app\modules\finance\models\FinanceTaxoffice;
use app\modules\finance\models\FinanceTaxofficeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * FinanceTaxofficeController implements the CRUD actions for FinanceTaxoffice model.
 */
class FinanceTaxofficeController extends Controller
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
     * Lists all FinanceTaxoffice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FinanceTaxofficeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new FinanceTaxoffice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FinanceTaxoffice();

        if ($model->load(Yii::$app->request->post())) {
            if (!$model->save()) {
                Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failure in saving the new Tax Office. Please try again."));
                return $this->redirect(['index']);
            }
            $user = Yii::$app->user->identity->username;
            $year = Yii::$app->session["working_year"];
            Yii::info('User ' . $user . ' working in year ' . $year . ' created new tax office.', 'financial');

            Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The item was created successfully."));
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing FinanceTaxoffice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if (!$model->save()) {
                Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failure in saving the changes. Please try again."));
                return $this->redirect(['index']);
            }
            $user = Yii::$app->user->identity->username;
            $year = Yii::$app->session["working_year"];
            Yii::info('User ' . $user . ' working in year ' . $year . ' updated the tax office with id ' . $id, 'financial');
            
            Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The item was updated successfully."));
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing FinanceTaxoffice model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (!$this->findModel($id)->delete()) {
            Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failure in deleting the item. Please try again."));
            return $this->redirect(['index']);
        }
        $user = Yii::$app->user->identity->username;
        $year = Yii::$app->session["working_year"];
        Yii::info('User ' . $user . ' working in year ' . $year . ' deleted the tax office with id ' . $id, 'financial');
        
        Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The item was deleted successfully."));
        return $this->redirect(['index']);
    }

    /**
     * Finds the FinanceTaxoffice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FinanceTaxoffice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FinanceTaxoffice::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
