<?php

namespace app\modules\finance\controllers;

use Yii;
use app\modules\finance\Module;
use app\modules\finance\models\FinanceSupplier;
use app\modules\finance\models\FinanceSupplierSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FinanceSupplierController implements the CRUD actions for FinanceSupplier model.
 */
class FinanceSupplierController extends Controller
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
        ];
    }
    
    /**
     * Lists all FinanceSupplier models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FinanceSupplierSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single FinanceSupplier model.
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
     * Creates a new FinanceSupplier model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FinanceSupplier();

        if ($model->load(Yii::$app->request->post())) {
            if(!$model->save()) {
                Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failure in saving the new supplier. Please try again."));
                return $this->redirect(['index']);
            }
            Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The new supplier was created successfully."));
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing FinanceSupplier model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if(!$model->save()) {
                Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failure in saving the changes. Please try again."));
                return $this->redirect(['index']);
            }
            Yii::$app->session->addFlash('info', Module::t('modules/finance/app', "The supplier was updated successfully."));
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing FinanceSupplier model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(!$this->findModel($id)->delete()){
            Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failure in deleting the supplier. Please try again."));
            return $this->redirect(['index', 'id' => $model->suppl_id]);
        }
        
        Yii::$app->session->addFlash('info', Module::t('modules/finance/app', "The supplier was deleted successfully."));
        return $this->redirect(['index']);
    }

    /**
     * Finds the FinanceSupplier model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FinanceSupplier the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FinanceSupplier::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
