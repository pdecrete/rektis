<?php

namespace app\modules\finance\controllers;

use Yii;
use app\modules\finance\Module;
use app\modules\finance\models\FinanceKae;
use app\modules\finance\models\FinanceKaewithdrawal;
use app\modules\finance\models\FinanceKaewithdrawalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\modules\finance\models\FinanceKaecredit;
use app\modules\finance\models\FinanceKaecreditpercentage;
use yii\base\Exception;
use app\modules\finance\components\Integrity;
use app\modules\finance\components\Money;

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
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [   'actions' => ['create', 'update', 'delete'],
                        'allow' => false,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Integrity::isLocked(Yii::$app->session["working_year"]);
                        },
                        'denyCallback' => function ($rule, $action) {
                            Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "The action is not permitted! The year you are working on is locked."));
                            return $this->redirect(['index']);
                        }
                        ],
                        [   'actions' =>['index'],
                            'allow' => true,
                            'roles' => ['financial_viewer'],
                        ],
                        [   'actions' =>['index', 'create', 'update', 'delete'],
                            'allow' => true,
                            'roles' => ['financial_director'],
                        ]
                    ]
            ],
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

        $kaewithdrsbalance = FinanceKaewithdrawal::getAllWithdrawalsBalance($kaesListModel, Yii::$app->session["working_year"]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'kaes' => $kaesListModel,
            'balances' => $kaewithdrsbalance,
        ]);
    }

    /**
     * Creates a new FinanceKaewithdrawal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        if (!isset($id) || !is_numeric($id)) {
            Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "The RCN for which the process was requested cound not be found."));
            return $this->redirect(['/finance/finance-kaewithdrawal/index']);
        }

        $model = new FinanceKaewithdrawal();
        $kaeCredit = FinanceKaecredit::findOne(['kae_id' => $id, 'year' => Yii::$app->session["working_year"]]);
        $kaeCreditSumPercentage = FinanceKaecreditpercentage::getKaeCreditSumPercentage($kaeCredit->kaecredit_id);
        $kae = FinanceKae::findOne(['kae_id' => $kaeCredit->kae_id]);
        $kaeWithdrwals = FinanceKaewithdrawal::find()->where(['kaecredit_id' => $kaeCredit->kaecredit_id])->all();
        //echo $kaeCredit->kaecredit_id;
        //echo "<pre>"; var_dump($kaewithdrwals); echo "</pre>";die();
        //$kae = FinanceKae::findOne(['kae_id' => $id]);

        if ($model->load(Yii::$app->request->post())) {
            try {
                $available = ($kaeCredit->kaecredit_amount)*(Money::toPercentage($kaeCreditSumPercentage, false)/100);
                $balance = $available - FinanceKaewithdrawal::getWithdrawsSum($kaeCredit->kaecredit_id);
                /*echo "Demanded: " . Money::toCents($model->kaewithdr_amount);
                echo "<br />";
                echo "Withdraws: " . FinanceKaewithdrawal::getWithdrawsSum($kaeCredit->kaecredit_id);
                echo "<br />";
                echo "Initial Available: " . $available;
                echo "<br />";
                echo "Ypoloipo: " . $balance;
                die();*/
                $model->kaecredit_id = $kaeCredit->kaecredit_id;
                $model->kaewithdr_date = date("Y-m-d H:i:s");
                $model->kaewithdr_amount = Money::toCents($model->kaewithdr_amount);
                if ($model->kaewithdr_amount <= 0 || ($model->kaewithdr_amount > $balance)) {
                    throw new Exception();
                }
                if (!$model->save()) {
                    throw new Exception();
                }
                Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The withdrawal completed successfully."));
                return $this->redirect(['index']);
            } catch (Exception $e) {
                Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failure in currying out the RCN withdrawal. Please check the declared the validity of the withdraw amount or contact with the administrator."));
                return $this->redirect(['/finance/finance-kaewithdrawal/index']);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'kae' => $kae,
                'kaeCredit' => $kaeCredit,
                'kaeCreditSumPercentage' => $kaeCreditSumPercentage,
                'kaeWithdrwals' => $kaeWithdrwals
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
        if (!isset($id) || !is_numeric($id)) {
            Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "The RCN for which the process was requested cound not be found."));
            return $this->redirect(['/finance/finance-kaewithdrawal/index']);
        }

        $model = $this->findModel($id);
        $model->kaewithdr_amount = Money::toCurrency($model->kaewithdr_amount);
        //echo "<pre>"; var_dump($model); echo "</pre>"; die();
        $kaeCredit = FinanceKaecredit::findOne(['kaecredit_id' => $model->kaecredit_id]);
        $kaeCreditSumPercentage = FinanceKaecreditpercentage::getKaeCreditSumPercentage($kaeCredit->kaecredit_id);
        $kae = FinanceKae::findOne(['kae_id' => $kaeCredit->kae_id]);
        $kaeWithdrwals = FinanceKaewithdrawal::find()->where(['kaecredit_id' => $model->kaecredit_id])->all();

        if ($model->load(Yii::$app->request->post())) {
            try {
                $oldModel = $this->findModel($id);
                $available = ($kaeCredit->kaecredit_amount)*Money::toPercentage($kaeCreditSumPercentage, false);
                $balance = $available - FinanceKaewithdrawal::getWithdrawsSum($kaeCredit->kaecredit_id);
                $model->kaewithdr_amount = Money::toCents($model->kaewithdr_amount);
                $newBalance = $balance - $oldModel->kaewithdr_amount + $model->kaewithdr_amount;

                if ($model->kaewithdr_amount <= 0 || ($newBalance < 0)) {
                    throw new Exception();
                }
                if (!$model->save()) {
                    throw new Exception();
                }

                Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The update of the withdrawal completed successfully."));
                return $this->redirect(['index', 'id' => $model->kaewithdr_id]);
            } catch (Exception $e) {
                Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failure in currying out the RCN withdrawal. Please check the declared the validity of the withdraw amount or contact with the administrator."));
                return $this->redirect(['/finance/finance-kaewithdrawal/index']);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'kae' => $kae,
                'kaeCredit' => $kaeCredit,
                'kaeCreditSumPercentage' => $kaeCreditSumPercentage,
                'kaeWithdrwals' => $kaeWithdrwals
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
        if (!$this->findModel($id)->delete()) {
            Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failure in deleting the RCN withdrawal. Please try again or contact with the administrator."));
            return $this->redirect(['/finance/finance-kaewithdrawal/index']);
        }

        Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The RCN Withdraw was deleted successfylly."));
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
