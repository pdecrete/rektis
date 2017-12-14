<?php

namespace app\modules\finance\controllers;

use app\modules\finance\Module;
use app\modules\finance\components\Money;
use app\modules\finance\models\FinanceKae;
use app\modules\finance\models\FinanceKaecreditpercentage;
use app\modules\finance\models\FinanceKaecreditpercentageSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\finance\models\FinanceKaecredit;
use app\modules\finance\models\FinanceKaewithdrawal;
use app\modules\finance\components\Integrity;

/**
 * FinanceKaecreditpercentageController implements the CRUD actions for FinanceKaecreditpercentage model.
 */
class FinanceKaecreditpercentageController extends Controller
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
                                [   'actions' =>['index', 'create', 'update', 'delete'], 
                                    'allow' => true,
                                    'roles' => ['@'],
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
     * Lists all FinanceKaecreditpercentage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FinanceKaecreditpercentageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $kaesListModel = FinanceKae::find()->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'kaes' => $kaesListModel
        ]);
    }

    /**
     * Creates a new FinanceKaecreditpercentage model for the RCN with code $id.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCreate($id)
    {
        if(!isset($id) || !is_numeric($id))
        {
            Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "The RCN for which the process was requested cound not be found."));
            return $this->redirect(['/finance/finance-kaecreditpercentage/index']);
        }
            
        $model = new FinanceKaecreditpercentage();
        $kae = FinanceKae::findOne(['kae_id' => $id]);
        $kaecredit = FinanceKaecredit::findOne(['kae_id' => $id]);
        $model->kaecredit_id = $kaecredit->kaecredit_id;

        if ($model->load(Yii::$app->request->post())){
            try{
                $newPercentage = Money::toDbPercentage($model->kaeperc_percentage);
                $model->kaeperc_percentage = $newPercentage;
                $currentPercentSum = FinanceKaecreditpercentage::getKaeCreditSumPercentage($kaecredit->kaecredit_id);
                if(($currentPercentSum +  $newPercentage) > 10000 || $newPercentage <= 0)  throw new \Exception();
                if(!$model->save()) throw new \Exception();
                Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "Your changes were saved succesfully."));
                return $this->redirect(['/finance/finance-kaecreditpercentage/index']);
            }
            catch(\Exception $exc){
                Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failure in saving changes. Please check the validity of the input data (e.g. percentage or percentages sum <= 100%) or contact with the administrator."));
                return $this->redirect(['/finance/finance-kaecreditpercentage/index']);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'kae' => $kae,
                'kaecredit' => $kaecredit
            ]);
        }
    }

    /**
     * Updates an existing FinanceKaecreditpercentage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $kae = $model->getKae()->one();
        $kaecredit = $model->getKaecredit()->one();
        
        if ($model->load(Yii::$app->request->post())){
            try{                
                $oldmodelcredit = $this->findModel($id)->kaeperc_percentage;                 
                $currentPercentSum = FinanceKaecreditpercentage::getKaeCreditSumPercentage($model->kaecredit_id);

                $model->kaeperc_percentage = Money::toDbPercentage($model->kaeperc_percentage);
                
                //echo strval(((int)$model->kaeperc_percentage + (int)$currentPercentSum - (int)$oldmodelcredit)); die();
                if($model->kaeperc_percentage > 10000 || $model->kaeperc_percentage <= 0 || 
                    ((int)$model->kaeperc_percentage + (int)$currentPercentSum - (int)$oldmodelcredit) > 10000) throw new \Exception();
                if(!$model->save()) throw new \Exception();
                Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "Your changes were saved succesfully."));
                return $this->redirect(['/finance/finance-kaecreditpercentage/index']);
            }
            catch(\Exception $exc){
                Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failure in saving changes. Please check the validity of the input data (e.g. percentage or percentages sum <= 100%) or contact with the administrator."));
                return $this->redirect(['/finance/finance-kaecreditpercentage/index']);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'kae' => $kae,
                'kaecredit' => $kaecredit
            ]);
        }
    }

    /**
     * Deletes an existing FinanceKaecreditpercentage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $percentgeModel = $this->findModel($id);
        $oldSumPercentage = FinanceKaecreditpercentage::getKaeCreditSumPercentage($percentgeModel->kaecredit_id);
        $newSumPercentage = $oldSumPercentage - $percentgeModel->kaeperc_percentage;
        $kaeCredit = FinanceKaecredit::findOne(['kaecredit_id' => $percentgeModel->kaecredit_id]);
        $sumWithdrawals = FinanceKaewithdrawal::getWithdrawsSum($percentgeModel->kaecredit_id);
        //echo $sumWithdrawals . "<br />";
        //echo $kaeCredit->kaecredit_amount*Money::toPercentage($newSumPercentage, false);
        //die();
        if($sumWithdrawals > $kaeCredit->kaecredit_amount*Money::toPercentage($newSumPercentage, false)){
            Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "The percentage attributed to the RCN cannot be deleted. The deletion would cause the withdrawals to exceed the total attributed amount to the RCN."));
            return $this->redirect(['index']);
        }
        
        if(!$percentgeModel->delete()){
            Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failure in deleting the percentage attributed to the RCN Credit. Please try again or contact with the administrator."));
            return $this->redirect(['index']);
        }
        
        Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The deletion of the percentage attributed to the RCN credit was completed successfully"));
        return $this->redirect(['index']);
    }

    /**
     * Finds the FinanceKaecreditpercentage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FinanceKaecreditpercentage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FinanceKaecreditpercentage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Module::t('modules/finance/app', 'The requested page does not exist.'));
        }
    }
}
