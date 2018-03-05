<?php

namespace app\modules\finance\controllers;

use Yii;
use app\modules\finance\Module;
use yii\base\Exception;
use app\modules\finance\models\FinanceDeduction;
use app\modules\finance\models\FinanceDeductionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
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
                'access' => [   'class' => AccessControl::className(),
                    'rules' =>  [
                        ['actions' => ['index'], 'allow' => true, 'roles' => ['financial_viewer']],
                        ['allow' => true, 'roles' => ['financial_editor']]
                    ]
                ]
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
            try {
                $model->deduct_downlimit = Money::toCents($model->deduct_downlimit);
                $model->deduct_uplimit = Money::toCents($model->deduct_uplimit);
                $model->deduct_percentage = Money::toDbPercentage($model->deduct_percentage);

                if (!$model->save()) {
                    throw new Exception();
                }

                $user = Yii::$app->user->identity->username;
                $year = Yii::$app->session["working_year"];
                Yii::info('User ' . $user . ' working in year ' . $year . ' created new deduction.', 'financial');

                Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The deduction was created successfully."));
                return $this->redirect(['index']);
            } catch (Exception $e) {
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
        if ($model->deduct_obsolete == 1) {
            Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "The deduction is obsolete and it cannot be edited."));
            return $this->redirect(['index']);
        }

        $model->deduct_date = date("Y-m-d H:i:s");

        if ($model->load(Yii::$app->request->post())) {
            try {
                $model->deduct_downlimit = Money::toCents($model->deduct_downlimit);
                $model->deduct_uplimit = Money::toCents($model->deduct_uplimit);
                $model->deduct_percentage = Money::toDbPercentage($model->deduct_percentage);

                if (!$model->save()) {
                    throw new Exception();
                }

                $user = Yii::$app->user->identity->username;
                $year = Yii::$app->session["working_year"];
                Yii::info('User ' . $user . ' working in year ' . $year . ' updated deduction with id ' . $id, 'financial');

                Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The deduction was updated successfully."));
                return $this->redirect(['index']);
            } catch (Exception $e) {
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
     * Sets as obselete an existing FinanceDeduction model.
     * If the action is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            $model = $this->findModel($id);
            if ($model->deduct_obsolete == 1) {
                throw new Exception("The deduction is already obsolete.");
            }

            $model->deduct_obsolete = 1;
            if ($model->deduct_id == 1 || $model->deduct_id == 2 || $model->deduct_id == 3) {
                throw new Exception("Deletion is not allowed for this type of deduction.");
            }
            if (!$model->save()) {
                throw new Exception("Failure in deleting deduction.");
            }

            $user = Yii::$app->user->identity->username;
            $year = Yii::$app->session["working_year"];
            Yii::info('User ' . $user . ' working in year ' . $year . ' set deduction with id ' . $id . ' as obselete.', 'financial');

            Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The deduction was set to obsolete."));
            return $this->redirect(['index']);
        } catch (Exception $e) {
            Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', $e->getMessage()));
            return $this->redirect(['index']);
        }
    }


    public function actionActivate($id)
    {
        try {
            $model = $this->findModel($id);
            if ($model->deduct_obsolete == 0) {
                throw new Exception("The deduction is already active.");
            }

            $model->deduct_obsolete = 0;

            if (!$model->save()) {
                throw new Exception("Failure in activating deduction.");
            }

            $user = Yii::$app->user->identity->username;
            $year = Yii::$app->session["working_year"];
            Yii::info('User ' . $user . ' working in year ' . $year . ' set deduction with id ' . $id . ' as active.', 'financial');

            Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The deduction was activated."));
            return $this->redirect(['index']);
        } catch (Exception $e) {
            Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', $e->getMessage()));
            return $this->redirect(['index']);
        }
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
