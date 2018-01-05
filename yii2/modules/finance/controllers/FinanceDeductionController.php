<?php

namespace app\modules\finance\controllers;

use Yii;
use app\modules\finance\Module;
use app\modules\finance\models\FinanceDeduction;
use app\modules\finance\models\FinanceDeductionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Exception;
use app\modules\finance\components\Money;

/**
 * FinanceDeductionController implements the CRUD actions for FinanceDeduction model.
 */
class FinanceDeductionController extends Controller
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
     * Lists all FinanceDeduction models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FinanceDeductionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new FinanceDeduction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FinanceDeduction();
        $model->deduct_date = date("Y-m-d H:i:s");

        if ($model->load(Yii::$app->request->post())) {
            try{
                $model->deduct_downlimit = Money::toCents($model->deduct_downlimit);
                $model->deduct_uplimit = Money::toCents($model->deduct_uplimit);
                $model->deduct_percentage = Money::toDbPercentage($model->deduct_percentage);

                if(!$model->save())
                    throw new Exception();
                Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The deduction was created successfully."));
                return $this->redirect(['index']);
            }
            catch(Exception $e){
                Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failure in creating deduction."));
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing FinanceDeduction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->deduct_date = date("Y-m-d H:i:s");
        
        if ($model->load(Yii::$app->request->post())) {
            try{
                $model->deduct_downlimit = Money::toCents($model->deduct_downlimit);
                $model->deduct_uplimit = Money::toCents($model->deduct_uplimit);
                $model->deduct_percentage = Money::toDbPercentage($model->deduct_percentage);

                if(!$model->save())
                Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The deduction was updated successfully."));
                return $this->redirect(['index']);
            }
            catch(Exception $e){
                Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failure in updating deduction."));
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing FinanceDeduction model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(!$this->findModel($id)->delete())
            Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failure in deleting deduction."));
        else
            Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The deduction was deleted successfully."));
            
        return $this->redirect(['index']);
    }

    /**
     * Finds the FinanceDeduction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FinanceDeduction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FinanceDeduction::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
