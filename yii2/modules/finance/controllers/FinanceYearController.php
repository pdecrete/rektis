<?php

namespace app\modules\finance\controllers;

use Yii;
use app\modules\finance\models\FinanceYear;
use app\modules\finance\models\FinanceYearSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use app\modules\finance\components\Money;
use yii\base\Exception;

/**
 * FinanceYearController implements the CRUD actions for FinanceYear model.
 */
class FinanceYearController extends Controller
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
     * Lists all FinanceYear models.
     * @return mixed
     */
    public function actionIndex()
    {
        //$searchModel = new FinanceYearSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //echo "<pre>"; print_r($dataProvider); echo "</pre>"; die();
        //return $this->render('index', [
        //     'searchModel' => $searchModel,
        //      'dataProvider' => $dataProvider,
        //]);
      
        $allModels = FinanceYear::find()->all();
        foreach($allModels as $yearItem)
            $yearItem->year_credit = Money::toCurrency($yearItem->year_credit);

        $dataProvider = new ArrayDataProvider([
            'allModels' => $allModels,
            'sort' => [
                'attributes' => ['year', 'year_credit', 'year_iscurrent', 'year_lock'],
            ],
        ]);
        return $this->render('index', ['dataProvider' => $dataProvider,]);
    }

    /**
     * Displays a single FinanceYear model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        
        $model = $this->findModel($id);
        $model->year_credit = Money::toCurrency($model->year_credit);
        
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new FinanceYear model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FinanceYear();

        if ($model->load(Yii::$app->request->post())){
            $model->year_credit = Money::toCents($model->year_credit);
            if($model->save())
                return $this->redirect(['view', 'id' => $model->year]);
        } 

        return $this->render('create', ['model' => $model]);
    }

    /**
     * Updates an existing FinanceYear model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->year_credit = Money::toCurrency($model->year_credit);
        
        if ($model->load(Yii::$app->request->post())){
            $model->year_credit = Money::toCents($model->year_credit);
            if($model->save())
                return $this->redirect(['view', 'id' => $model->year]);
        } 
        
        return $this->render('update', ['model' => $model,]);
    }

    public function actionLock($id)
    {
        $model = $this->findModel($id);      
        $model->year_lock = 1;
        if(!$model->save())
        {
            Yii::$app->session->addFlash('danger', "Αποτυχία κλειδώματος του οικομομικού έτους " . $id);
            return $this->redirect(['/finance/finance-year']);
        }
        
        Yii::$app->session->addFlash('success', "Το κλείδωμα του οικονομικό έτος " . $id . " ολοκληρώθηκε επιτυχώς.");
        return $this->redirect(['/finance/finance-year']);
    }
    
    /**
     * Deletes an existing FinanceYear model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try{
            $this->findModel($id)->delete();
            Yii::$app->session->addFlash('success', "To οικομομικό έτος " . $id . " διαγράφηκε επιτυχώς.");
            return $this->redirect(['index']);
        }
        catch(Exception $exc){
            Yii::$app->session->addFlash('danger', "Αποτυχία διαγραφής του οικομομικού έτους " . $id . ".");
            return $this->redirect(['/finance/finance-year']);
        }
    }

    /**
     * Finds the FinanceYear model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FinanceYear the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FinanceYear::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
