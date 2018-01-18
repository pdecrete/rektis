<?php

namespace app\modules\finance\controllers;

use Yii;
use app\modules\finance\Module;
use app\modules\finance\models\FinanceInvoice;
use app\modules\finance\models\FinanceInvoiceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\base\Exception;
use yii\filters\VerbFilter;
use app\modules\finance\models\FinanceSupplier;
use app\modules\finance\models\FinanceExpenditure;
use app\modules\finance\models\FinanceInvoicetype;

/**
 * FinanceInvoiceController implements the CRUD actions for FinanceInvoice model.
 */
class FinanceInvoiceController extends Controller
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
     * Lists all FinanceInvoice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FinanceInvoiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single FinanceInvoice model.
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
     * Creates a new FinanceInvoice model for the expenditure with id $id.
     * If creation is successful, the browser will be redirected to the expendinture 'index' page.
     * 
     * @param integer $id
     * @return mixed
     */
    public function actionCreate($id)
    {
        $invoice_model = new FinanceInvoice();
        $expenditure_model = FinanceExpenditure::findOne(['exp_id' => $id]);
        $supplier_model = FinanceSupplier::findOne(['suppl_id' => $expenditure_model->suppl_id]);
        $invoicetypes_model = FinanceInvoicetype::find()->all();

        $invoice_model->suppl_id = $supplier_model->suppl_id;
        $invoice_model->exp_id = $expenditure_model->exp_id;
        $invoice_model->inv_deleted = 0;
        
        if ($invoice_model->load(Yii::$app->request->post())){
            try{                
                if(!$invoice_model->save()) 
                    throw new Exception();                               
                Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The invoice was created successfully."));
                return $this->redirect(['/finance/finance-expenditure']);
            }
            catch(Exception $e){
                Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failure in creating invoice."));
                return $this->redirect(['/finance/finance-expenditure']);
            }
        } else {
            return $this->render('create', [
                'invoice_model' => $invoice_model,
                'expenditure_model' => $expenditure_model,
                'supplier_model' => $supplier_model,
                'invoicetypes_model' => $invoicetypes_model
            ]);
        }
    }

    /**
     * Updates an existing FinanceInvoice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {  
        $invoice_model = $this->findModel($id);
        $expenditure_model = FinanceExpenditure::findOne(['exp_id' => $invoice_model->exp_id]);
        $supplier_model = FinanceSupplier::findOne(['suppl_id' => $expenditure_model->suppl_id]);
        $invoicetypes_model = FinanceInvoicetype::find()->all();
                
        if ($invoice_model->load(Yii::$app->request->post())){
            try{
                if(!$invoice_model->save())
                    throw new Exception();
                Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The invoice was created successfully."));
                return $this->redirect(['/finance/finance-expenditure']);
            }
            catch(Exception $e){
                Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failure in creating invoice."));
                return $this->redirect(['/finance/finance-expenditure']);
            }
        } else {
            return $this->render('create', [
                'invoice_model' => $invoice_model,
                'expenditure_model' => $expenditure_model,
                'supplier_model' => $supplier_model,
                'invoicetypes_model' => $invoicetypes_model
            ]);
        }
    }

    /**
     * Deletes an existing FinanceInvoice model.
     * If deletion is successful, the browser will be redirected to the expenditures 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(!$this->findModel($id)->delete()){            
            Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failure in deleting invoice."));
            return $this->redirect(['/finance/finance-expenditure']);
        }
        Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The invoice was deleted succesfully."));
        return $this->redirect(['/finance/finance-expenditure']);
    }

    /**
     * Finds the FinanceInvoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FinanceInvoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FinanceInvoice::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
