<?php

namespace app\modules\finance\controllers;

use Yii;
use app\modules\finance\Module;
use app\modules\finance\models\FinanceKae;
use app\modules\finance\models\FinanceKaewithdrawal;
use app\modules\finance\models\FinanceKaewithdrawalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\finance\models\FinanceKaecredit;
use app\modules\finance\models\FinanceKaecreditpercentage;

/**
 * FinanceKaewithdrawalController implements the CRUD actions for FinanceKaewithdrawal model.
 */
class FinanceKaewithdrawalController extends Controller
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
     * Lists all FinanceKaewithdrawal models.
     * @return mixed
     */
    public function actionIndex()
    {       
        $searchModel = new FinanceKaewithdrawalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $kaesListModel = FinanceKae::find()->all();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'kaes' => $kaesListModel
        ]);
    }

    /**
     * Creates a new FinanceKaewithdrawal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        if(!isset($id) || !is_numeric($id))
        {
            Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "The RCN for which the process was requested cound not be found."));
            return $this->redirect(['/finance/finance-kaewithdrawal/index']);
        }
        
        $model = new FinanceKaewithdrawal();
        $kaeCredit = FinanceKaecredit::findOne(['kae_id' => $id, 'year' => Yii::$app->session["working_year"]]);
        $kaeCreditSumPercentage = FinanceKaecreditpercentage::getKaeCreditSumPercentage($kaeCredit->kaecredit_id);
        $kae = FinanceKae::findOne(['kae_id' => $kaeCredit->kae_id]);
        
        //echo "<pre>"; var_dump($kaeCreditSumPercentage); echo "</pre>";die();
        //$kae = FinanceKae::findOne(['kae_id' => $id]);

        if ($model->load(Yii::$app->request->post())) {
            if(!$model->save())
            {
                Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failure in currying out the RCN withdrawal. Please try again or contact with the administrator."));
                return $this->redirect(['/finance/finance-kaewithdrawal/index']);
            }
            Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The withdrawal completed successfully."));
            return $this->redirect(['view', 'id' => $model->kaewithdr_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing FinanceKaewithdrawal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->kaewithdr_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing FinanceKaewithdrawal model.
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
     * Finds the FinanceKaewithdrawal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FinanceKaewithdrawal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FinanceKaewithdrawal::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
