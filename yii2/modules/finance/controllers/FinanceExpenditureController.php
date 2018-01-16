<?php

namespace app\modules\finance\controllers;

use Yii;
use yii\base\Model;
use app\modules\finance\Module;
use app\modules\finance\models\FinanceExpenditure;
use app\modules\finance\models\FinanceExpenditureSearch;
use app\modules\finance\models\FinanceKae;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\finance\models\FinanceFpa;
use app\modules\finance\components\Money;
use yii\base\Exception;
use app\modules\finance\models\FinanceKaewithdrawal;
use app\modules\finance\models\FinanceKaecredit;
use app\modules\finance\models\FinanceExpendwithdrawal;
use app\modules\finance\models\FinanceExpenditurestate;
use app\modules\finance\models\FinanceSupplier;
use app\modules\finance\models\FinanceInvoice;
use app\modules\finance\models\FinanceDeduction;
use app\modules\finance\models\FinanceExpenddeduction;

/**
 * FinanceExpenditureController implements the CRUD actions for FinanceExpenditure model.
 */
class FinanceExpenditureController extends Controller
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
     * Lists all FinanceExpenditure models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FinanceExpenditureSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $kaesListModel = FinanceKae::find()->all();
        $expendwithdrawals = array();
        $prefix = Yii::$app->db->tablePrefix;
        $expwithdr = $prefix . 'finance_expendwithdrawal';
        $wthdr = $prefix . "finance_kaewithdrawal";
        
        foreach($dataProvider->models as $expend_model){
            $withdrawal_model = (new \yii\db\Query())
            ->select($expwithdr . '.*,'. $wthdr . '.*' )
            ->from([$expwithdr, $wthdr])
            ->where($expwithdr . '.kaewithdr_id=' . $wthdr . '.kaewithdr_id AND' . ' exp_id =' . $expend_model['exp_id'])
            ->all();
            $expendwithdrawals[$expend_model['exp_id']]['WITHDRAWAL'] = $withdrawal_model;
            for($i = 0; $i < count($withdrawal_model); $i++)
                $expendwithdrawals[$expend_model['exp_id']]['EXPENDWITHDRAWAL'][$i] = 
                    FinanceExpendwithdrawal::find()->
                    where(['exp_id' => $expend_model['exp_id'], 'kaewithdr_id' => $withdrawal_model[$i]['kaewithdr_id']])->one()['expwithdr_amount'];
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'kaes' => $kaesListModel,
            'expendwithdrawals' => $expendwithdrawals
        ]);
    }


    /**
     * Creates a new FinanceExpenditure model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        if(!isset($id) || !is_numeric($id)){
            Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "The requested expenditure could not be found."));
            return $this->redirect(['/finance/finance-kaewithdrawal/index']);
        }
        $suppliers = FinanceSupplier::find()->all();
        $kaecredit_id = FinanceKaecredit::find()->where(['kae_id' => $id, 'year' => Yii::$app->session["working_year"]])->one()->kaecredit_id;
        $kaewithdrawals = FinanceKaewithdrawal::find()->where(['kaecredit_id' => $kaecredit_id])->all();
        
        $i = 0;
        $expendwithdrawals_models = array();
        foreach($kaewithdrawals as $key=>$kaewithdrawal){
            if(FinanceExpendwithdrawal::getWithdrawalBalance($kaewithdrawal->kaewithdr_id) > 0){
                $expendwithdrawals_models[$i++] = new FinanceExpendwithdrawal();
            }
            else
                unset($kaewithdrawals[$key]);
        }
        
        $deductions = FinanceDeduction::find()->all();
        $expenddeduction_models = array();
        for($i = 0; $i < count($deductions); $i++)
            $expenddeduction_models[$i] = new FinanceExpenddeduction();
        
        //echo "<pre>"; print_r($deductions); echo "</pre>";
        //die();
        
        $model = new FinanceExpenditure();
        $vat_levels = FinanceFpa::find()->all();

        foreach ($vat_levels as $vat_level)
            $vat_level->fpa_value = Money::toPercentage($vat_level->fpa_value);
        
        if ($model->load(Yii::$app->request->post()) && Model::loadMultiple($expendwithdrawals_models, Yii::$app->request->post()))// $expendwithdrawals_models->load(Yii::$app->request->post()))
        {
            //echo "<pre>"; print_r($expendwithdrawals_models); echo "</pre>";
            //die();
            try{
                $transaction = Yii::$app->db->beginTransaction();                              
                $model->fpa_value = Money::toDbPercentage($model->fpa_value);                
                $model->exp_date = date("Y-m-d H:i:s");
                $model->exp_deleted = 0;
                $model->exp_lock = 0;
                /*echo "<pre>"; print_r($model->toArray()); echo "</pre>";
                if(!$model->validate()){
                    print_r($model->getErrors());
                    die();
                }*/
                if(!$model->save()) throw new Exception();
                
                $expend_state_model = new FinanceExpenditurestate();
                $expend_state_model->exp_id = $model->exp_id;
                $expend_state_model->state_id = 1;
                $expend_state_model->expstate_date = date("Y-m-d H:i:s");
                echo "<pre>"; print_r($expend_state_model->toArray()); echo "</pre>";
                if(!$expend_state_model->save()) throw new Exception();
                
                $partial_amount = $model->exp_amount; 
                foreach ($expendwithdrawals_models as $expendwithdrawals_model){
                    $expendwithdrawals_model->exp_id = $model->exp_id;
                    $withdrawal_balance = FinanceExpendwithdrawal::getWithdrawalBalance($expendwithdrawals_model->kaewithdr_id);
                    if($partial_amount > $withdrawal_balance){
                        $expendwithdrawals_model->expwithdr_amount = $withdrawal_balance;
                        $partial_amount = $partial_amount - $withdrawal_balance;
                    }
                    else {
                        $expendwithdrawals_model->expwithdr_amount = $partial_amount;
                        $partial_amount = 0;
                        //echo "<pre>"; print_r($expendwithdrawals_model->toArray()); echo "</pre>"; die();
                        if(!$expendwithdrawals_model->save()) 
                            throw new Exception();
                        break;
                    }
                    //echo "<pre>"; print_r($expendwithdrawals_model->toArray()); echo "</pre>"; die();
                    if(!$expendwithdrawals_model->save()) throw new Exception();
                }
                if($partial_amount > 0) throw new Exception();
                
                $transaction->commit();
                Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The expenditure was created successfully."));
                return $this->redirect(['index']);
            }
            catch(Exception $e){
                $transaction->rollBack();
                Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failed to create expenditure."));
                return $this->redirect(['index']);
            }
        } 
        else 
        {
            return $this->render('create', [
                'model' => $model,
                'expendwithdrawals_models' => $expendwithdrawals_models,
                'vat_levels' => $vat_levels,
                'kaewithdrawals' => $kaewithdrawals,
                'suppliers' => $suppliers,
                'expenddeduction_models' => $expenddeduction_models,
                'deductions' => $deductions
            ]);
        }
    }

    /**
     * Updates an existing FinanceExpenditure model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing FinanceExpenditure model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {        
        if(!isset($id) || !is_numeric($id)){
            Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "The requested expenditure could not be found."));
            return $this->redirect(['/finance/finance-kaewithdrawal/index']);
        }
        $stopedhere = "";
        $expenditure = $this->findModel($id);        
        try{
            $transaction = Yii::$app->db->beginTransaction();
            if(!FinanceExpendwithdrawal::deleteAll(['exp_id' => $expenditure->exp_id]))
                throw new Exception();
            if(!FinanceExpenditurestate::deleteAll(['exp_id' => $expenditure->exp_id]))
                throw new Exception();
            if(FinanceInvoice::find(['exp_id' => $expenditure->exp_id])->where(['exp_id' => $expenditure->exp_id])->count() != 0)
                if(!FinanceInvoice::deleteAll(['exp_id' => $expenditure->exp_id]))
                    throw new Exception();
            if(!$expenditure->delete())
                throw new Exception();
            $transaction->commit();
            Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The expenditure was deleted successfully."));
            return $this->redirect(['index']);
        }
        catch(Exception $e){
            $transaction->rollBack();
            Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failed to delete expenditure." . $stopedhere));
            return $this->redirect(['index']);
        }
        return $this->redirect(['index']);
    }

    /**
     * Sets the expenditure state to the next state (e.g. if it is in the "Initial" state, then the 
     * state is set to "Demanded")
     * If the action is successful, the next visual indicator will be shown.
     * @param integer $id
     * @return mixed
     */
    public function actionForwardstate($id)
    {
        if(!isset($id) || !is_numeric($id)){
            Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "The requested expenditure could not be found."));
            return $this->redirect(['/finance/finance-expenditure/index']);
        }
        
        $exp_model = $this->findModel($id);
        $state_model = new FinanceExpenditurestate();
        $state_model->exp_id = $exp_model->exp_id;
        //$supplier = FinanceSupplier::find()->where(['suppl_id' => $exp_model->suppl_id])->one()->suppl_name;
                
        if ($state_model->load(Yii::$app->request->post())){
            try{
                $statescount = FinanceExpenditurestate::find()->where(['exp_id' => $state_model->exp_id])->count();
                //echo $state_model->exp_id . "---" . $statescount; die();
                if($statescount < 0 || $statescount >= 4) throw new Exception();
                $state_model->state_id = $statescount + 1;
                if(!$state_model->save())  throw new Exception();
                Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The expenditure's state changed successfully."));
                return $this->redirect(['index']);
            }
            catch(Exception $e){
                Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failed to change expenditure's state."));
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('forwardstate', [
                'state_model' => $state_model,
                //'supplier' => $supplier
            ]);
        }
    }
    
    /**
     * Sets the expenditure state to the next state (e.g. if it is in the "Demanded" state, then the 
     * state is set to "Initial")
     * If the action is successful, the visual indicators will be shown appropriately.
     * @param integer $id
     * @return mixed
     */
    public function actionBackwardstate($id)
    {
        if(!isset($id) || !is_numeric($id)){
            Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "The requested expenditure could not be found."));
            return $this->redirect(['/finance/finance-expenditure/index']);
        }

        try{
            $statescount = FinanceExpenditurestate::find()->where(['exp_id' => $id])->count();
            if($statescount <= 1 || $statescount > 4) throw new Exception();
            if(!FinanceExpenditureState::find()->
                where(['exp_id' => $id, 'state_id' => $statescount])->one()->delete())
                throw new Exception();
            Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The expenditure's state changed successfully."));
            return $this->redirect(['index']);           
        }
        catch(Exception $e){
            Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failed to change expenditure's state."));
            return $this->redirect(['index']);
        }
    }

    
    /**
     * Finds the FinanceExpenditure model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FinanceExpenditure the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FinanceExpenditure::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
