<?php

namespace app\modules\finance\controllers;

use Yii;
use app\modules\finance\Module;
use app\modules\finance\models\FinanceFpa;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\modules\finance\components\Money;

/**
 * FinanceFpaController implements the CRUD actions for FinanceFpa model.
 */
class FinanceFpaController extends Controller
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
                ]],
                'access' => [   'class' => AccessControl::className(),
                    'rules' =>  [
                        ['actions' => ['index'], 'allow' => true, 'roles' => ['financial_viewer']],
                        ['allow' => true, 'roles' => ['financial_editor']]
                    ]
                ]
        ];
    }

    /**
     * Lists all FinanceFpa models.
     * @return mixed
     */
    public function actionIndex()
    {
        $vatOptions = FinanceFpa::find()->all();
        foreach ($vatOptions as $vatOption) {
            $vatOption->fpa_value = Money::toPercentage($vatOption->fpa_value);
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $vatOptions,
        ]);
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    /**
     * Creates a new FinanceFpa model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FinanceFpa();

        if ($model->load(Yii::$app->request->post())) {
            try {
                $model->fpa_value = Money::toDbPercentage($model->fpa_value);
                if ($model->fpa_value < 0 || $model->fpa_value > 10000) {
                    throw new \Exception();
                }
                if (!$model->save()) {
                    throw new \Exception();
                }

                $user = Yii::$app->user->identity->username;
                $year = Yii::$app->session["working_year"];
                Yii::info('User ' . $user . ' working in year ' . $year . ' created new VAT level.', 'financial');

                Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The item was created successfully."));
                return $this->redirect(['index']);
            } catch (\Exception $exc) {
                Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failure in creating the requested item."));
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('create', ['model' => $model]);
        }
    }

    /**
     * Updates an existing FinanceFpa model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            try {
                $model->fpa_value = Money::toDbPercentage($model->fpa_value);
                if ($model->fpa_value < 0 || $model->fpa_value > 10000) {
                    throw new \Exception();
                }
                if (!$model->save()) {
                    throw new \Exception();
                }

                $user = Yii::$app->user->identity->username;
                $year = Yii::$app->session["working_year"];
                Yii::info('User ' . $user . ' working in year ' . $year . ' updated VAT level with id ' . $id, 'financial');

                Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The item was updated successfully."));
                return $this->redirect(['index']);
            } catch (\Exception $exc) {
                Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failure in updating the requested item."));
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('update', ['model' => $model]);
        }
    }

    /**
     * Deletes an existing FinanceFpa model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (!$this->findModel($id)->delete()) {
            $user = Yii::$app->user->identity->username;
            $year = Yii::$app->session["working_year"];
            Yii::info('User ' . $user . ' working in year ' . $year . ' deleted VAT level with id ' . $id, 'financial');

            Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failure in deleting the item."));
            return $this->redirect(['index']);
        }
        Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The item was deleted successfully."));
        return $this->redirect(['index']);
    }

    /**
     * Finds the FinanceFpa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FinanceFpa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FinanceFpa::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
