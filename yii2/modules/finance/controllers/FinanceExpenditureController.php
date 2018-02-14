<?php

namespace app\modules\finance\controllers;

use Yii;
use kartik\mpdf\Pdf;
use yii\base\Model;
use app\modules\finance\Module;
use app\modules\finance\models\FinanceExpenditure;
use app\modules\finance\models\FinanceExpenditureSearch;
use app\modules\finance\models\FinanceKae;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\modules\finance\models\FinanceFpa;
use app\modules\finance\components\Integrity;
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
use app\modules\finance\models\FinanceState;



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
        return  ['verbs' =>  [   'class' => VerbFilter::className(),
                                'actions' => ['delete' => ['POST']]],
                'access' => [   'class' => AccessControl::className(),
                                'rules' =>  [
                                            [   'actions' => ['create', 'delete', 'forwardstate', 'updatestate', 'backwardstate'],
                                                'allow' => false,
                                                'roles' => ['@'],
                                                'matchCallback' => function ($rule, $action) {                                                    
                                                                    return Integrity::isLocked(Yii::$app->session["working_year"]);
                                                                },
                                                'denyCallback' => function ($rule, $action) {
                                                                    Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "The action is not permitted! The year is locked."));
                                                                    return $this->redirect(['index']);
                                                                  }
                                            ],
                                                ['actions' => ['index'], 'allow' => true, 'roles' => ['financial_viewer']],
                                                ['allow' => true, 'roles' => ['financial_editor']]
                                            ]]
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
        
        $kaewithdrsbalance = FinanceKaewithdrawal::getAllWithdrawalsBalance($kaesListModel, Yii::$app->session["working_year"]);
        
        foreach($dataProvider->models as $expend_model){
            $withdrawal_model = (new \yii\db\Query())
            ->select($expwithdr . '.*,'. $wthdr . '.*' )
            ->from([$expwithdr, $wthdr])
            ->where($expwithdr . '.kaewithdr_id=' . $wthdr . '.kaewithdr_id AND' . ' exp_id =' . $expend_model['exp_id'])
            ->all();
            
            $invoice = FinanceInvoice::find()->where(['exp_id' => $expend_model['exp_id']])->one()['inv_id'];
            
            $expendwithdrawals[$expend_model['exp_id']]['WITHDRAWAL'] = $withdrawal_model;
            
            $expendwithdrawals[$expend_model['exp_id']]['INVOICE'] = $invoice;
            
            for($i = 0; $i < count($withdrawal_model); $i++)
            {                
                $kaewithdrawal = FinanceExpendwithdrawal::find()
                ->where(['exp_id' => $expend_model['exp_id'], 'kaewithdr_id' => $withdrawal_model[$i]['kaewithdr_id']])
                ->one();
                
                $kaecredit_id = FinanceKaewithdrawal::find()
                ->where(['kaewithdr_id' => $kaewithdrawal['kaewithdr_id']])
                ->one()['kaecredit_id']; 
                
                $expendwithdrawals[$expend_model['exp_id']]['EXPENDWITHDRAWAL'][$i] = $kaewithdrawal['expwithdr_amount'];
                
                //$expendwithdrawals[$expend_model['exp_id']]['RELATEDKAE'] = 
                //FinanceKaecredit::find()->where(['kaecredit_id' => $kaecredit_id])->one()['kae_id'];
            }
        }
        //echo "<pre>"; print_r($expendwithdrawals); echo "</pre>";die();
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'kaes' => $kaesListModel,
            'expendwithdrawals' => $expendwithdrawals,
            'balances' => $kaewithdrsbalance,
        ]);
    }


    /**
     * Creates a new FinanceExpenditure model for the RCN with number $id.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * 
     * @param integer $id
     * @return mixed
     */
    public function actionCreate($id)
    {
        if(!isset($id) || !is_numeric($id)){
            Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "The requested expenditure could not be found."));
            return $this->redirect(['/finance/finance-kaewithdrawal/index']);
        }

        $suppliers = FinanceSupplier::find()->orderBy('suppl_name')->all();
        $kaecredit_id = FinanceKaecredit::find()->where(['kae_id' => $id, 'year' => Yii::$app->session["working_year"]])->one()->kaecredit_id;
        
        $kaewithdrawals = FinanceKaewithdrawal::find()->where(['kaecredit_id' => $kaecredit_id])->all();
                
        $i = 0;
        $expendwithdrawals_models = array();
        foreach($kaewithdrawals as $key=>$kaewithdrawal){
            $kaewithdrawal->kaewithdr_amount = Money::toCurrency($kaewithdrawal->kaewithdr_amount, true);
            if(FinanceExpendwithdrawal::getWithdrawalBalance($kaewithdrawal->kaewithdr_id) > 0){
                $expendwithdrawals_models[$i++] = new FinanceExpendwithdrawal();
            }
            else
                unset($kaewithdrawals[$key]);
        }
        
        if(count($expendwithdrawals_models) == 0){
            Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "There is no withdrawal for this RCN to create expenditure."));
            return $this->redirect(['index']);
        }
        
        $deductions = FinanceDeduction::find()->all();
        $expenddeduction_models = array();
        for($i = 3; $i <= count($deductions); $i++) //3 for the first three deductions presented as radiolist
            $expenddeduction_models[$i-3] = new FinanceExpenddeduction();
        
        $model = new FinanceExpenditure();
        $vat_levels = FinanceFpa::find()->all();

        foreach ($vat_levels as $vat_level)
            $vat_level->fpa_value = Money::toPercentage($vat_level->fpa_value);
        
        if ($model->load(Yii::$app->request->post()) 
            && Model::loadMultiple($expendwithdrawals_models, Yii::$app->request->post())
            && Model::loadMultiple($expenddeduction_models, Yii::$app->request->post()))
        {
            $this->saveModels($model, $expendwithdrawals_models, $expenddeduction_models);
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
     * Updates an existing FinanceExpenditure model for the expenditure with id $id.
     * If update is successful, the browser will be redirected to the 'view' page.
     * 
     * @param integer $id
     * @return mixed
     */
    
    public function actionUpdate($id)
    {   
        return $this->redirect(['index']);
        if(!isset($id) || !is_numeric($id)){
            Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "The requested expenditure could not be found."));
            return $this->redirect(['/finance/finance-kaewithdrawal/index']);
        }
        $model = $this->findModel($id);
        $model->exp_amount = Money::toCurrency($model->exp_amount);
        
        $suppliers = FinanceSupplier::find()->all();            
       
        $kaewithdr_id = FinanceExpendwithdrawal::find()->where(['exp_id' => $id])->all()[0]->kaewithdr_id;
        $kaecredit_id = FinanceKaewithdrawal::find()->where(['kaewithdr_id' => $kaewithdr_id])->all()[0]->kaecredit_id;              
        $kaewithdrawals = FinanceKaewithdrawal::find()->where(['kaecredit_id' => $kaecredit_id])->all();

        $i = 0;
        $expendwithdrawals_models = array();
        foreach($kaewithdrawals as $key=>$kaewithdrawal){
            $kaewithdrawal->kaewithdr_amount = Money::toCurrency($kaewithdrawal->kaewithdr_amount, true);
            if(FinanceExpendwithdrawal::getWithdrawalBalance($kaewithdrawal->kaewithdr_id) > 0 || 
                !is_null(FinanceExpendwithdrawal::findOne(['exp_id' => $id, 'kaewithdr_id' => $kaewithdrawal->kaewithdr_id]))){
                
                $exp_withdr_exists = FinanceExpendwithdrawal::find()->where(['exp_id' => $id])->andWhere(['kaewithdr_id' => $kaewithdrawal->kaewithdr_id])->one();
                if(!is_null($exp_withdr_exists))
                    $expendwithdrawals_models[$i] = $exp_withdr_exists;
                else 
                    $expendwithdrawals_models[$i] = new FinanceExpendwithdrawal();
                $i++;
            }
            else
                unset($kaewithdrawals[$key]);
        }
        
        if(count($expendwithdrawals_models) == 0){
            Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "There is no withdrawal for this RCN to create expenditure."));
            return $this->redirect(['index']);
        }  
            
        $deductions = FinanceDeduction::find()->all();
        //echo "<pre>"; print_r($deductions); echo "</pre>"; die();       
        
        $expenddeduction_models = array();
        $exp_deduction = FinanceExpenddeduction::find()->where(['exp_id' => $id])->andWhere(['<=', 'deduct_id', 3])->all();

        if(count($exp_deduction))
            $expenddeduction_models[0] = $exp_deduction;
        for($i = 3; $i < count($deductions); $i++){
            $exp_deductions_checkbox = FinanceExpenddeduction::find()->where(['exp_id' => $id, 'deduct_id'=> $deductions[$i]->deduct_id])->one();
//            echo "<strong>In update";  echo "</strong>";
//            echo "<pre>"; print_r($exp_deductions_checkbox); echo "</pre>"; die();
            if(count($exp_deductions_checkbox))
                $expenddeduction_models[$i-2] = $exp_deductions_checkbox;
            else {    
                $expenddeduction_models[$i-2] = new FinanceExpenddeduction();
                $expenddeduction_models[$i-2]->exp_id = $id;
            }
        }
        //echo "<pre>"; print_r($expenddeduction_models); echo "<br /><br />"; echo count($expenddeduction_models); echo "</pre>";die();
        
        $vat_levels = FinanceFpa::find()->all();
        
        foreach ($vat_levels as $vat_level)
            $vat_level->fpa_value = Money::toPercentage($vat_level->fpa_value);
            
        if ($model->load(Yii::$app->request->post())
            && Model::loadMultiple($expendwithdrawals_models, Yii::$app->request->post())
            && Model::loadMultiple($expenddeduction_models, Yii::$app->request->post()))
        {
            
            $this->saveModels($model, $expendwithdrawals_models, $expenddeduction_models, false);
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
    private function saveModels($model, $expendwithdrawals_models, $expenddeduction_models, $new_expenditure = true)
    {   
        try{
            $transaction = Yii::$app->db->beginTransaction();
            $model->exp_amount = Money::toCents($model->exp_amount); 
            $model->fpa_value = Money::toDbPercentage($model->fpa_value);
            $model->exp_date = date("Y-m-d H:i:s");
            $model->exp_deleted = 0;
            $model->exp_lock = 0;

            if(!$model->save()) throw new Exception();
            
            if(!$new_expenditure){
                $old_expdeductions = FinanceExpenddeduction::find()->where(['exp_id' => $model->exp_id])
                                                                   ->andWhere(['>', 'deduct_id', 3])->all();
                //echo "<pre>"; print_r($old_expdeductions); echo "</pre>"; die();
                foreach ($old_expdeductions as $old_expdeduction)
                    if(!$old_expdeduction->delete()) throw new Exception();
            }
            else {               
                $expend_state_model = new FinanceExpenditurestate();
                $expend_state_model->exp_id = $model->exp_id;
                $expend_state_model->state_id = 1;
                $expend_state_model->expstate_date = date("Y-m-d H:i:s");
                if(!$expend_state_model->save()) throw new Exception();
            }
            
            //echo "<pre>"; print_r($expenddeduction_models); echo "</pre>"; die();
            for($i = 0; $i < count($expenddeduction_models); $i++){
                $expenddeduction_models[$i]->exp_id = $model->exp_id;
                $tmp = $expenddeduction_models[$i]->deduct_id;
                
                if(isset($tmp) && $tmp != 0 && $tmp != null){
                    //echo "I saved " . $tmp;
                    if(!$expenddeduction_models[$i]->save()) throw new Exception();
                }
            }
            //die();
            
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
                    
                    if(!$expendwithdrawals_model->save())
                        throw new Exception();
                    break;
                }
                
                if(!$expendwithdrawals_model->save()) 
                    throw new Exception();
                
            }
           // echo $partial_amount; die();
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
            if(!FinanceExpenddeduction::deleteAll(['exp_id' => $expenditure->exp_id]))
                throw new Exception();
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
        
        $invoice = FinanceInvoice::findOne(['exp_id' => $id]);
        if(is_null($invoice)){
            Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "The state of the expenditure cannot change. Please create voucher first."));
            return $this->redirect(['index']);
        }        
        
        $exp_model = $this->findModel($id);
        $current_state = FinanceExpenditurestate::find()->where(['exp_id' => $exp_model->exp_id, ])->max('state_id');
        $current_state_name = FinanceState::findOne(['state_id' => $current_state])['state_name']; 
        //echo "<pre>"; var_dump($current_state); echo "</pre>"; die();
        $state_model = new FinanceExpenditurestate();
        $state_model->exp_id = $exp_model->exp_id;
        //$supplier = FinanceSupplier::find()->where(['suppl_id' => $exp_model->suppl_id])->one()->suppl_name;
                
        if ($state_model->load(Yii::$app->request->post())){
            try{
                $statescount = FinanceExpenditurestate::find()->where(['exp_id' => $state_model->exp_id])->count();
                //echo $state_model->exp_id . "---" . $statescount; die();
                if($statescount < 0 || $statescount >= 4) 
                    throw new Exception();
                $state_model->state_id = $statescount + 1;
                if(!$state_model->save())  
                    throw new Exception();
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
                'current_state_name' => $current_state_name,
            ]);
        }
    }
    
    
    /**
     * Sets the expenditure state to the next state (e.g. if it is in the "Initial" state, then the
     * state is set to "Demanded")
     * If the action is successful, the next visual indicator will be shown.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdatestate($state_id, $exp_id)
    {
        try{
            $state_model = FinanceExpenditurestate::findOne(['state_id' => $state_id, 'exp_id' => $exp_id]);
            
            if(is_null($state_model)) 
                throw new Exception();
                                        
            $current_state_name = FinanceState::findOne(['state_id' => $state_id])['state_name'];
            
            if ($state_model->load(Yii::$app->request->post())){                
                if(!$state_model->save()) 
                    throw new Exception();
            
                Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The expenditure's state details were updated successfully."));
                return $this->redirect(['index']);
            }
            else{
                return $this->render('updatestate', [
                    'state_model' => $state_model,
                    'current_state_name' => $current_state_name,
                ]);                
            }            
        }
        catch (Exception $e){
            Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failed to update expenditure's state details."));
            return $this->redirect(['index']);
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
     * Creates the Expedinture Payment Report (pdf file) for the expenditure with $id
     * @param integer $id
     * @return mixed
     */
    public function actionPaymentreport(){
        $models = array();
        $kae = "";
        //echo "<pre>"; print_r(Yii::$app->request->post('selection')); echo "</pre>"; die();
        $exp_ids = Yii::$app->request->post('selection');
        //echo "<pre>"; print_r($exp_ids); echo "</pre>"; die();
        
        try{
            if(is_null($exp_ids))
                throw new Exception();
            foreach($exp_ids as $index=>$id){
                $expenditure_model = FinanceExpenditure::findOne(['exp_id' => $id]);
                $supplier_model = FinanceSupplier::findOne(['suppl_id' => $expenditure_model['suppl_id']]);
                $invoice_model = FinanceInvoice::findOne(['exp_id' => $expenditure_model['exp_id']]);

                if(is_null($invoice_model))
                    throw new Exception();
                
                $models[$index]['EXPENDITURE'] = $expenditure_model;
                $models[$index]['SUPPLIER'] = $supplier_model;
                $models[$index]['INVOICE'] = $invoice_model;
            
                $kaewithdr_id = FinanceExpendwithdrawal::find()->
                                    where(['exp_id' => $expenditure_model['exp_id']])->all()[0]['kaewithdr_id'];
                $kaecredit_id = FinanceKaewithdrawal::findOne(['kaewithdr_id' => $kaewithdr_id])['kaecredit_id'];        
                $exp_kae = FinanceKaecredit::findOne(['kaecredit_id' => $kaecredit_id])['kae_id'];
                if($kae == "")
                    $kae = $exp_kae;
                else if($exp_kae != $kae)
                    throw new Exception();
            }
            $year = Yii::$app->session["working_year"];
        }
        catch(Exception $e){
            Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failed to create Payments Report. Please check the selected expenditures (should be of the same RCN and to have an assigned voucher)."));
            return $this->redirect(['index']);
        }
        $content = $this->renderPartial('paymentreport', [
            'models' => $models,
            'year' => $year,
            'kae' => $kae,
        ]);
                
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            'filename' => 'aitisi.pdf',
            'destination' => Pdf::DEST_DOWNLOAD,
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'options' => ['title' => 'Περιφερειακή Διεύθυνση Πρωτοβάθμιας και Δευτεροβάθμιας Εκπαίδευσης Κρήτης'],
        ]);
        return $pdf->render();
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
