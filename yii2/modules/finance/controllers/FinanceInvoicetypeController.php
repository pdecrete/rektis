<?php

namespace app\modules\finance\controllers;

use Yii;
use app\modules\finance\models\FinanceInvoicetype;
use app\modules\finance\models\FinanceInvoicetypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\modules\finance\Module;
use yii\base\Exception;

/**
 * FinanceInvoicetypeController implements the CRUD actions for FinanceInvoicetype model.
 */
class FinanceInvoicetypeController extends Controller
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
                ]]
        ];
    }

    /**
     * Lists all FinanceInvoicetype models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FinanceInvoicetypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new FinanceInvoicetype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FinanceInvoicetype();

        if ($model->load(Yii::$app->request->post())) {
            try{
                if(!$model->save())
                    throw new Exception();

                $user = Yii::$app->user->identity->username;
                $year = Yii::$app->session["working_year"];
                Yii::info('User ' . $user . ' working in year ' . $year . ' created new invoice type.', 'financial');                   
                    
                Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The voucher type was updated successfully."));
                return $this->redirect(['index']);
            }
            catch(Exception $e){
                Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failure in updating voucher type."));
                return $this->redirect(['index']);
            }
        } 
        else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing FinanceInvoicetype model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            try{
                if(!$model->save())
                    throw new Exception();
                    
                $user = Yii::$app->user->identity->username;
                $year = Yii::$app->session["working_year"];
                Yii::info('User ' . $user . ' working in year ' . $year . ' updated invoice type with id ' . $id, 'financial');
                    
                Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The voucher type was updated successfully."));
                return $this->redirect(['index']);
            }
            catch(Exception $e){
                Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failure in updating voucher type."));
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing FinanceInvoicetype model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(!$this->findModel($id)->delete())
            Yii::$app->session->addFlash('danger', Module::t('modules/finance/app', "Failure in deleting voucher type."));
        else {
            $user = Yii::$app->user->identity->username;
            $year = Yii::$app->session["working_year"];
            Yii::info('User ' . $user . ' working in year ' . $year . ' deleted invoice type with id ' . $id, 'financial');
            
            Yii::$app->session->addFlash('success', Module::t('modules/finance/app', "The voucher type was deleted succesfully."));
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the FinanceInvoicetype model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FinanceInvoicetype the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FinanceInvoicetype::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
